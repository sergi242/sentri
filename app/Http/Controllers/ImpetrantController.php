<?php

namespace App\Http\Controllers;

use App\Models\Impetrant;
use App\Models\ImpetrantDocument;
use App\Models\Pays;
use App\Models\Departement;
use App\Models\Arrondissement;
use App\Models\Quartier;
use Illuminate\Http\Request;
use App\Models\CasierNote;
use App\Models\SimilarityRejection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ImpetrantController extends Controller
{
    // =========================================================================
    // MÉTHODES EXISTANTES — inchangées
    // =========================================================================

    public function casier($id)
    {
        $impetrant = \App\Models\Impetrant::with([
            'demandes.contentieux',
            'demandes.documents',
            'casierNotes.user'
        ])->findOrFail($id);

        $demandes = $impetrant->demandes()->where('retire', 0)->get();

        $totalContentieux = $demandes->filter(fn($d) =>
            $d->statut_demande === 'Envoyée au contentieux'
        )->count();

        $demandesEnContentieux = $demandes->filter(fn($d) =>
            $d->statut_demande === 'Envoyée au contentieux'
        );

        $demandesExpirees = $demandes->filter(fn($d) =>
            $d->date_expiration !== null &&
            \Carbon\Carbon::parse($d->date_expiration)->isPast() &&
            $d->attribue == 1
        )->count();

        $fichesExpirees = $demandes->filter(fn($d) =>
            $d->date_validiter_fiche !== null &&
            \Carbon\Carbon::parse($d->date_validiter_fiche)->isPast() &&
            $d->statut_demande === "En attente d'approbation"
        )->count();

        $watchlistMatches = \App\Models\Watchlist::where('actif', true)->get()->filter(function($w) use ($impetrant) {
            $points = 0;
            if ($w->nom && strtoupper($w->nom) === strtoupper($impetrant->nom)) $points += 30;
            if ($w->prenom && strtolower($w->prenom) === strtolower($impetrant->prenom)) $points += 20;
            if ($w->date_naissance && $w->date_naissance == $impetrant->date_naissance) $points += 30;
            return $points >= 60;
        });

        $documentsExpires = $demandes->flatMap(fn($d) => $d->documents)
            ->filter(fn($doc) =>
                $doc->date_expiration !== null &&
                \Carbon\Carbon::parse($doc->date_expiration)->isPast()
            );

        $scoreRisque = 0;
        $scoreRisque += $totalContentieux     * 20;
        $scoreRisque += $demandesExpirees     * 10;
        $scoreRisque += $fichesExpirees       * 5;
        $scoreRisque += $watchlistMatches->count() * 40;
        $scoreRisque += $documentsExpires->count() * 5;
        $scoreRisque = min(100, $scoreRisque);

        $niveauRisque = match(true) {
            $scoreRisque >= 70 => ['label' => 'ÉLEVÉ',  'color' => 'danger'],
            $scoreRisque >= 40 => ['label' => 'MOYEN',  'color' => 'warning'],
            default            => ['label' => 'FAIBLE', 'color' => 'success'],
        };

        app(\App\Services\InfractionService::class)->syncPourImpetrant($impetrant);

        $notes = $impetrant->casierNotes()->with('user')->latest()->get();

        $infractions = $impetrant->infractions()
            ->with(['demande', 'user', 'preuves'])
            ->orderBy('date_infraction', 'desc')
            ->get();

        $scoreIndiscipline = min(100, $infractions->sum(fn($i) => $i->poids()));
        $totalInfractions  = $infractions->count();

        return view('admin.impetrants.casier', compact(
            'impetrant', 'demandes', 'totalContentieux', 'demandesEnContentieux',
            'demandesExpirees', 'fichesExpirees', 'watchlistMatches', 'documentsExpires',
            'scoreRisque', 'niveauRisque', 'notes', 'infractions',
            'scoreIndiscipline', 'totalInfractions',
        ));
    }

    public function storePreuve(Request $request, $id)
    {
        $request->validate([
            'preuves'   => 'required|array|max:5',
            'preuves.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $infraction = \App\Models\Infraction::findOrFail($id);

        foreach ($request->file('preuves') as $file) {
            $chemin = $file->store('infractions/preuves', 'public');
            \App\Models\InfractionPreuve::create([
                'infraction_id'  => $infraction->id,
                'chemin_fichier' => $chemin,
                'nom_original'   => $file->getClientOriginalName(),
            ]);
        }

        toastr()->success('Preuve(s) ajoutée(s)');
        return back();
    }

    public function deletePreuve($id)
    {
        $preuve = \App\Models\InfractionPreuve::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($preuve->chemin_fichier);
        $preuve->delete();
        toastr()->success('Preuve supprimée');
        return back();
    }

    public function storeNote(Request $request, $id)
    {
        $request->validate([
            'note'   => 'required|string|max:1000',
            'niveau' => 'required|in:info,warning,danger',
        ]);

        CasierNote::create([
            'impetrant_id' => $id,
            'user_id'      => auth()->id(),
            'note'         => $request->note,
            'niveau'       => $request->niveau,
        ]);

        toastr()->success('Note ajoutée avec succès');
        return back();
    }

    public function deleteNote($noteId)
    {
        $note = CasierNote::findOrFail($noteId);
        $note->delete();
        toastr()->success('Note supprimée');
        return back();
    }

    public function index(Request $request)
    {
        $query = Impetrant::withCount('demandes')
            ->with(['pays', 'demandes' => fn($q) => $q->orderBy('created_at', 'desc')]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) =>
                $q->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%")
            );
        }

        if ($request->filled('nationalite')) {
            $query->where('nationalites_id', $request->nationalite);
        }

        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->filled('nb_demandes')) {
            match($request->nb_demandes) {
                '1'  => $query->having('demandes_count', '=', 1),
                '2'  => $query->having('demandes_count', '=', 2),
                '3+' => $query->having('demandes_count', '>=', 3),
            };
        }

        $demandes = $query->paginate(10)->withQueryString();
        $pays     = \App\Models\Pays::orderBy('lib_pays')->get(['id', 'lib_pays']);
        $status   = "La liste des impétrants";

        return view('admin.impetrants.index', compact('demandes', 'status', 'pays'));
    }

    public function storeInfraction(Request $request, $id)
    {
        $request->validate([
            'motif'           => 'required|string|max:1000',
            'gravite'         => 'required|in:mineur,moyen,grave',
            'date_infraction' => 'required|date',
            'demande_id'      => 'nullable|exists:demandes,id',
        ]);

        \App\Models\Infraction::create([
            'impetrant_id'    => $id,
            'demande_id'      => $request->demande_id,
            'user_id'         => auth()->id(),
            'type'            => 'manuelle',
            'gravite'         => $request->gravite,
            'statut'          => 'en_cours',
            'motif'           => $request->motif,
            'date_infraction' => $request->date_infraction,
            'auto_generee'    => false,
        ]);

        toastr()->success('Infraction ajoutée au casier');
        return back();
    }

    public function updateStatutInfraction(Request $request, $id)
    {
        $infraction = \App\Models\Infraction::findOrFail($id);
        $request->validate(['statut' => 'required|in:en_cours,resolu,classe']);
        $infraction->update(['statut' => $request->statut]);
        toastr()->success('Statut mis à jour');
        return back();
    }

    public function deleteInfraction($id)
    {
        \App\Models\Infraction::findOrFail($id)->delete();
        toastr()->success('Infraction supprimée');
        return back();
    }

    public function destroy($id)
    {
        $impetrant = Impetrant::withCount('demandes')->findOrFail($id);

        if ($impetrant->demandes_count > 0) {
            return redirect()->back()
                ->with('error', "Impossible de supprimer : l'impétrant a des demandes.");
        }

        $impetrant->delete();

        return redirect()->route('impetrants.index')
            ->with('success', "Impétrant supprimé définitivement.");
    }

    public function demandes($id)
    {
        $impetrant = Impetrant::with([
            'pays',
            'demandes',
            'documents',
            'documents.paysDelivrance',
            'documents.createur',
        ])->findOrFail($id);

        $latestDemandePhoto = $impetrant->demandes
            ->sortByDesc('created_at')
            ->whereNotNull('photo')
            ->first()?->photo ?? null;

        return view('admin.demandes.detailsdemandes', compact('impetrant', 'latestDemandePhoto'));
    }

    public function casierGlobal(Request $request)
    {
        $search         = $request->get('search');
        $nationalite    = $request->get('nationalite');
        $niveauRisque   = $request->get('niveau_risque');
        $typeAntecedant = $request->get('type_antecedant');

        $query = \App\Models\Impetrant::query()
            ->where(function($q) {
                $q->whereHas('demandes', fn($d) =>
                    $d->where('statut_demande', 'Envoyée au contentieux')
                )
                ->orWhereHas('demandes', fn($d) =>
                    $d->where('date_validiter_fiche', '<', now())
                      ->where('statut_demande', "En attente d'approbation")
                )
                ->orWhereDoesntHave('demandes', fn($d) =>
                    $d->where('statut_demande', 'Approuvée')
                      ->where('date_expiration', '>=', now())
                );
            })
            ->when($search, fn($q) =>
                $q->where(fn($s) =>
                    $s->where('nom', 'like', "%$search%")
                      ->orWhere('prenom', 'like', "%$search%")
                )
            )
            ->when($nationalite, fn($q) =>
                $q->where('nationalites_id', $nationalite)
            )
            ->with([
                'demandes' => fn($q) => $q->select(
                    'id', 'impetrants_id', 'statut_demande',
                    'date_expiration', 'date_validiter_fiche',
                    'attribue', 'updated_at', 'photo'
                )->orderByDesc('updated_at'),
                'demandes.documents' => fn($q) => $q->select(
                    'id', 'demandes_id', 'type_document',
                    'numero_document', 'date_expiration'
                ),
                'pays:id,lib_pays',
            ])
            ->select('id', 'nom', 'prenom', 'date_naissance', 'nationalites_id')
            ->paginate(10);

        $watchlists = \App\Models\Watchlist::where('actif', true)
            ->select('nom', 'prenom', 'date_naissance')
            ->get();

        $results = $query->through(function($imp) use ($watchlists) {
            $demandes = $imp->demandes;

            $totalContentieux = $demandes->where('statut_demande', 'Envoyée au contentieux')->count();

            $demandesExpirees = $demandes->filter(fn($d) =>
                $d->date_expiration &&
                \Carbon\Carbon::parse($d->date_expiration)->isPast() &&
                $d->attribue == 1
            )->count();

            $fichesExpirees = $demandes->filter(fn($d) =>
                $d->date_validiter_fiche &&
                \Carbon\Carbon::parse($d->date_validiter_fiche)->isPast() &&
                $d->statut_demande === "En attente d'approbation"
            )->count();

            $documentsExpires = $demandes->flatMap(fn($d) => $d->documents)
                ->filter(fn($doc) =>
                    $doc->date_expiration &&
                    \Carbon\Carbon::parse($doc->date_expiration)->isPast()
                )->count();

            $watchlistCount = $watchlists->filter(function($w) use ($imp) {
                $points = 0;
                if ($w->nom && strtoupper($w->nom) === strtoupper($imp->nom)) $points += 30;
                if ($w->prenom && strtolower($w->prenom) === strtolower($imp->prenom)) $points += 20;
                if ($w->date_naissance && $w->date_naissance == $imp->date_naissance) $points += 30;
                return $points >= 60;
            })->count();

            $antecedants = [];
            if ($totalContentieux > 0) $antecedants[] = 'contentieux';
            if ($watchlistCount > 0)   $antecedants[] = 'watchlist';
            if ($documentsExpires > 0) $antecedants[] = 'documents_expires';
            if ($fichesExpirees > 0)   $antecedants[] = 'fiches_expirees';

            $score = min(100,
                $totalContentieux * 20 +
                $demandesExpirees * 10 +
                $fichesExpirees   * 5  +
                $watchlistCount   * 40 +
                $documentsExpires * 5
            );

            $niveau = match(true) {
                $score >= 70 => 'eleve',
                $score >= 40 => 'moyen',
                default      => 'faible',
            };

            return [
                'impetrant'        => $imp,
                'score'            => $score,
                'niveau'           => $niveau,
                'totalContentieux' => $totalContentieux,
                'demandesExpirees' => $demandesExpirees,
                'fichesExpirees'   => $fichesExpirees,
                'documentsExpires' => $documentsExpires,
                'watchlistCount'   => $watchlistCount,
                'antecedants'      => $antecedants,
                'derniere_activite'=> $demandes->max('updated_at'),
            ];
        });

        if ($niveauRisque || $typeAntecedant) {
            $filtered = collect($results->items())->filter(function($r) use ($niveauRisque, $typeAntecedant) {
                if ($niveauRisque   && $r['niveau'] !== $niveauRisque) return false;
                if ($typeAntecedant && !in_array($typeAntecedant, $r['antecedants'])) return false;
                return true;
            })->values();
            $results->setCollection($filtered);
        }

        $pays = \App\Models\Pays::orderBy('lib_pays')->get(['id', 'lib_pays']);

        return view('admin.impetrants.casier_global', compact(
            'results', 'pays', 'search', 'nationalite', 'niveauRisque', 'typeAntecedant'
        ));
    }

    // =========================================================================
    // ENREGISTREMENT DIRECT (sans demande)
    // =========================================================================

    public function create()
    {
        $pays               = Pays::orderBy('lib_pays')->get();
        $departements       = Departement::orderBy('lib_departement')->get();
        $allArrondissements = Arrondissement::select('id', 'lib_arrondissement', 'departements_id')
                                ->orderBy('lib_arrondissement')->get();
        $allQuartiers       = Quartier::select('id', 'lib_quartier', 'arrondissements_id')
                                ->orderBy('lib_quartier')->get();

        return view('admin.impetrants.create-direct', compact(
            'pays', 'departements', 'allArrondissements', 'allQuartiers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'             => 'required|string|max:255',
            'prenom'          => 'required|string|max:255',
            'sexe'            => 'required|in:Masculin,Féminin',
            'date_naissance'  => 'required|date|before:today',
            'nationalites_id' => 'required|exists:pays,id',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'nom.required'             => 'Le nom est obligatoire.',
            'prenom.required'          => 'Le prénom est obligatoire.',
            'sexe.required'            => 'Le sexe est obligatoire.',
            'sexe.in'                  => 'Le sexe doit être Masculin ou Féminin.',
            'date_naissance.required'  => 'La date de naissance est obligatoire.',
            'date_naissance.before'    => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'nationalites_id.required' => 'La nationalité est obligatoire.',
        ]);

        // ── Anti-doublon ──────────────────────────────────────────
        $uniqueString = strtoupper(trim($request->nom))
            . strtoupper(trim($request->prenom))
            . $request->sexe
            . $request->date_naissance
            . $request->nationalites_id;

        $existant = Impetrant::where('unique_string', $uniqueString)->first();
        if ($existant) {
            return back()->withInput()
                ->with('doublon_id', $existant->id)
                ->with('doublon_nom', $existant->nom . ' ' . $existant->prenom)
                ->withErrors(['doublon' =>
                    "Cet impétrant existe déjà dans le système (#{$existant->id})."
                ]);
        }

        DB::beginTransaction();
        try {
            // ── Upload photo impétrant ────────────────────────────
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file      = $request->file('photo');
                $filename  = 'imp_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('app'), $filename);
                $photoPath = $filename;
            }

            $impetrant = Impetrant::create([
                'nom'            => strtoupper(trim($request->nom)),
                'prenom'         => trim($request->prenom),
                'sexe'           => $request->sexe,
                'date_naissance' => $request->date_naissance,
                'lieu_naissance' => $request->lieu_naissance  ?? '',
                'nationalites_id'=> $request->nationalites_id,
                'nom_pere'       => $request->nom_pere        ?? '',
                'prenom_pere'    => $request->prenom_pere     ?? '',
                'nom_mere'       => $request->nom_mere        ?? '',
                'prenom_mere'    => $request->prenom_mere     ?? '',
                'photo'          => $photoPath,
                'source'         => 'direct',
                'created_by'     => Auth::id(),
                'unique_string'  => $uniqueString,
            ]);

            // ── Sauvegarde document si numéro fourni ──────────────
            if (!empty($request->numero_document)) {
                $docExistant = \App\Models\ImpetrantDocument::where('impetrants_id', $impetrant->id)
                    ->where('numero_document', $request->numero_document)
                    ->first();

                if (!$docExistant) {
                    \App\Models\ImpetrantDocument::create([
                        'impetrants_id'      => $impetrant->id,
                        'type_document'      => $request->type_document      ?? 'Passeport',
                        'numero_document'    => strtoupper(trim($request->numero_document)),
                        'date_delivrance'    => $request->date_delivrance     ?: null,
                        'date_expiration'    => $request->date_expiration     ?: null,
                        'pays_delivrance_id' => $request->pays_delivrance_id  ?: null,
                        'mrz'                => $request->h_mrz               ?: null,
                        'source'             => $request->h_source_doc        ?? 'manuel',
                        'created_by'         => Auth::id(),
                    ]);
                }
            }
            // ──────────────────────────────────────────────────────

            DB::commit();
            toastr()->success('Impétrant enregistré avec succès.');
            return redirect()->route('impetrants.demandes', $impetrant->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('ImpetrantController@store : ' . $e->getMessage());
            toastr()->error("Une erreur est survenue : " . $e->getMessage());
            return back()->withInput();
        }
    }

    public function show($id)
    {
        $impetrant = Impetrant::with([
            'pays',
            'demandes',
            'documents',
            'documents.paysDelivrance',
            'documents.createur',
        ])->findOrFail($id);

        return view('admin.impetrants.show', compact('impetrant'));
    }

    public function edit(string $id)
    {
        $impetrant          = Impetrant::findOrFail($id);
        $pays               = Pays::orderBy('lib_pays')->get();
        $departements       = Departement::orderBy('lib_departement')->get();
        $allArrondissements = Arrondissement::select('id', 'lib_arrondissement', 'departements_id')
                                ->orderBy('lib_arrondissement')->get();
        $allQuartiers       = Quartier::select('id', 'lib_quartier', 'arrondissements_id')
                                ->orderBy('lib_quartier')->get();

        return view('admin.impetrants.edit', compact(
            'impetrant', 'pays', 'departements', 'allArrondissements', 'allQuartiers'
        ));
    }

    public function update(Request $request, string $id)
    {
        $impetrant = Impetrant::findOrFail($id);

        $request->validate([
            'nom'             => 'required|string|max:255',
            'prenom'          => 'required|string|max:255',
            'sexe'            => 'required|in:Masculin,Féminin',
            'date_naissance'  => 'required|date|before:today',
            'lieu_naissance'  => 'nullable|string|max:255',
            'nationalites_id' => 'required|exists:pays,id',
            'nom_pere'        => 'nullable|string|max:255',
            'prenom_pere'     => 'nullable|string|max:255',
            'nom_mere'        => 'nullable|string|max:255',
            'prenom_mere'     => 'nullable|string|max:255',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'nom.required'             => 'Le nom est obligatoire.',
            'prenom.required'          => 'Le prénom est obligatoire.',
            'sexe.required'            => 'Le sexe est obligatoire.',
            'sexe.in'                  => 'Le sexe doit être Masculin ou Féminin.',
            'date_naissance.required'  => 'La date de naissance est obligatoire.',
            'nationalites_id.required' => 'La nationalité est obligatoire.',
        ]);

        DB::beginTransaction();
        try {
            // ── Upload photo ──────────────────────────────────────
            if ($request->hasFile('photo')) {
                if ($impetrant->photo && file_exists(public_path('app/' . $impetrant->photo))) {
                    unlink(public_path('app/' . $impetrant->photo));
                }
                $file     = $request->file('photo');
                $filename = 'imp_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('app'), $filename);
                $impetrant->photo = $filename;
            }

            $uniqueString = strtoupper(trim($request->nom))
                . strtoupper(trim($request->prenom))
                . $request->sexe
                . $request->date_naissance
                . $request->nationalites_id;

            $impetrant->update([
                'nom'            => strtoupper(trim($request->nom)),
                'prenom'         => trim($request->prenom),
                'sexe'           => $request->sexe,
                'date_naissance' => $request->date_naissance,
                'lieu_naissance' => $request->lieu_naissance  ?? '',
                'nationalites_id'=> $request->nationalites_id,
                'nom_pere'       => $request->nom_pere        ?? '',
                'prenom_pere'    => $request->prenom_pere     ?? '',
                'nom_mere'       => $request->nom_mere        ?? '',
                'prenom_mere'    => $request->prenom_mere     ?? '',
                'unique_string'  => $uniqueString,
            ]);

            // ── Sauvegarde document si numéro fourni ──────────────
            if (!empty($request->numero_document)) {
                $docExistant = ImpetrantDocument::where('impetrants_id', $impetrant->id)
                    ->where('numero_document', $request->numero_document)
                    ->first();

                if (!$docExistant) {
                    ImpetrantDocument::create([
                        'impetrants_id'      => $impetrant->id,
                        'type_document'      => $request->type_document ?? 'Passeport',
                        'numero_document'    => $request->numero_document,
                        'date_delivrance'    => $request->date_delivrance    ?: null,
                        'date_expiration'    => $request->date_expiration    ?: null,
                        'pays_delivrance_id' => $request->pays_delivrance_id ?: null,
                        'mrz'                => $request->h_mrz              ?: null,
                        'source'             => $request->h_source_doc       ?? 'manuel',
                        'created_by'         => auth()->id(),
                    ]);
                }
            }
            // ──────────────────────────────────────────────────────

            DB::commit();
            toastr()->success('Impétrant mis à jour avec succès.');
            return redirect()->route('impetrants.demandes', $impetrant->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('ImpetrantController@update : ' . $e->getMessage());
            toastr()->error("Erreur lors de la mise à jour.");
            return back()->withInput();
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // POST /impetrants/{impetrant}/documents
    // Ajout manuel d'un document depuis la fiche impétrant
    // ─────────────────────────────────────────────────────────────────
    public function storeDocument(Request $request, Impetrant $impetrant)
    {
        $request->validate([
            'type_document'      => 'required|in:Passeport,Titre de voyage,Laissez-passer,Autre',
            'numero_document'    => 'required|string|max:50',
            'date_delivrance'    => 'nullable|date',
            'date_expiration_doc'=> 'nullable|date',
        ]);

        $numero = strtoupper(trim($request->numero_document));

        // Anti-doublon : ce numéro existe-t-il déjà pour cet impétrant ?
        $existe = ImpetrantDocument::where('impetrants_id', $impetrant->id)
            ->where('numero_document', $numero)
            ->exists();

        if ($existe) {
            toastr()->warning('Ce numéro de document est déjà enregistré pour cet impétrant.');
            return back();
        }

        // Alerte si ce numéro appartient à un AUTRE impétrant
        $autreImpetrant = ImpetrantDocument::where('numero_document', $numero)
            ->where('impetrants_id', '!=', $impetrant->id)
            ->with('impetrant')
            ->first();

        DB::beginTransaction();
        try {
            ImpetrantDocument::create([
                'impetrants_id'      => $impetrant->id,
                'type_document'      => $request->type_document,
                'numero_document'    => $numero,
                'date_delivrance'    => $request->date_delivrance    ?: null,
                'date_expiration'    => $request->date_expiration_doc ?: null,
                'pays_delivrance_id' => null,
                'source'             => 'manuel',
                'created_by'         => auth()->id(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error('Erreur lors de l\'enregistrement : ' . $e->getMessage());
            return back();
        }

        if ($autreImpetrant) {
            toastr()->warning(
                'Document enregistré, mais attention : ce numéro était déjà associé à ' .
                $autreImpetrant->impetrant->nom . ' ' . $autreImpetrant->impetrant->prenom
            );
        } else {
            toastr()->success('Document enregistré avec succès.');
        }

        return back();
    }

    // ─────────────────────────────────────────────────────────────────
    // GET /api/impetrants/check-document?numero=XXXX
    // Vérification AJAX : ce numéro de document est-il déjà connu ?
    // Retourne les clés attendues par le JS du modal : trouve, nom, prenom, url_fiche
    // ─────────────────────────────────────────────────────────────────
    public function checkDocument(Request $request)
    {
        $numero = strtoupper(trim($request->get('numero', '')));

        if (empty($numero)) {
            return response()->json(['trouve' => false]);
        }

        $doc = ImpetrantDocument::where('numero_document', $numero)
            ->with('impetrant')
            ->first();

        if (!$doc || !$doc->impetrant) {
            return response()->json(['trouve' => false]);
        }

        return response()->json([
            'trouve'    => true,
            'nom'       => $doc->impetrant->nom,
            'prenom'    => $doc->impetrant->prenom,
            'url_fiche' => route('impetrants.demandes', $doc->impetrant->id),
        ]);
    }

    // ── Vérification doublon AJAX ─────────────────────────────────
    public function checkDoublon(Request $request)
    {
        $nom    = strtoupper(trim($request->nom    ?? ''));
        $prenom = strtoupper(trim($request->prenom ?? ''));
        $sexe   = $request->sexe            ?? '';
        $dn     = $request->date_naissance  ?? '';
        $nat    = $request->nationalites_id ?? '';

        if (!$nom || !$prenom || !$sexe || !$dn || !$nat) {
            return response()->json(['doublon' => false]);
        }

        $uniqueString = $nom . $prenom . $sexe . $dn . $nat;
        $existant     = Impetrant::where('unique_string', $uniqueString)->first();

        return response()->json([
            'doublon' => $existant !== null,
            'id'      => $existant?->id,
            'nom'     => $existant ? ($existant->nom . ' ' . $existant->prenom) : null,
        ]);
    }
}