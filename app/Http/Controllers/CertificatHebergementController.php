<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pays;
use App\Models\Demande;
use App\Models\Employeur;
use App\Models\Hebergeur;
use App\Models\Impetrant;
use App\Models\Departement;
use App\Models\FicheDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\CertificatHebergement;
use App\Models\CategorieSocioProfessionnelle;

class CertificatHebergementController extends Controller
{
    // =========================================================================
    // INDEX — Liste de tous les certificats
    // =========================================================================
    public function index(Request $request)
    {
        $query = CertificatHebergement::with([
            'hebergeurCongolais', 'hebergeurEtranger', 'hebergeurSociete', 'heberge', 'createur'
        ])->orderByDesc('created_at');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('numero_certificat', 'like', "%{$s}%")
                  ->orWhereHas('hebergeurCongolais', fn($r) => $r->where('nom', 'like', "%{$s}%")->orWhere('prenom', 'like', "%{$s}%")->orWhere('code_hebergeur', $s))
                  ->orWhereHas('hebergeurEtranger',  fn($r) => $r->where('nom', 'like', "%{$s}%")->orWhere('prenom', 'like', "%{$s}%"))
                  ->orWhereHas('hebergeurSociete',   fn($r) => $r->where('nom_employeur', 'like', "%{$s}%"))
                  ->orWhereHas('heberge',             fn($r) => $r->where('nom', 'like', "%{$s}%")->orWhere('prenom', 'like', "%{$s}%"));
            });
        }

        $certificats = $query->paginate(20)->withQueryString();

        $stats = [
            'total'      => CertificatHebergement::count(),
            'en_attente' => CertificatHebergement::where('statut', 'En attente')->count(),
            'valides'    => CertificatHebergement::where('statut', 'Validé')->count(),
            'expires'    => CertificatHebergement::where('statut', 'Expiré')->count(),
        ];

        return view('admin.certificats-hebergement.index', compact('certificats', 'stats'));
    }

    // =========================================================================
    // CREATE — Formulaire de création (multi-étapes)
    // =========================================================================
    public function create(Request $request)
    {
        $departements = Departement::all();
        $pays         = Pays::orderBy('lib_pays')->get();

        // Si un numéro de certificat est passé en paramètre (depuis newcrt/newvisa)
        $numeroCertificat = $request->get('numero_certificat');
        $certificatExistant = null;
        if ($numeroCertificat) {
            $certificatExistant = CertificatHebergement::with([
                'hebergeurCongolais', 'hebergeurEtranger', 'hebergeurSociete', 'heberge'
            ])->where('numero_certificat', $numeroCertificat)->first();
        }

        return view('admin.certificats-hebergement.create', compact(
            'departements', 'pays', 'numeroCertificat', 'certificatExistant'
        ));
    }

    // =========================================================================
    // STORE — Enregistrement du certificat
    // =========================================================================
    public function store(Request $request)
    {
        $request->validate([
            'hebergeur_type'      => 'required|in:Congolais,Etranger,Societe',
            'date_arrivee_prevue' => 'required|date',
            'date_depart_prevue'  => 'required|date|after:date_arrivee_prevue',
            'motif_sejour'        => 'nullable|string',
            'type_relation'       => 'required|in:Famille,Ami,Professionnel,Autre',
        ]);

        DB::beginTransaction();
        try {
            $hebergeurId = null;

            // ── ÉTAPE 1 : Résoudre l'hébergeur ───────────────────────────────
            switch ($request->hebergeur_type) {

                case 'Congolais':
                    if ($request->filled('hebergeur_existant_id')) {
                        // Hébergeur congolais déjà dans la base
                        $hebergeurId = $request->hebergeur_existant_id;
                    } else {
                        // Créer un nouvel hébergeur congolais
                        $request->validate([
                            'heb_nom'          => 'required|string',
                            'heb_prenom'       => 'required|string',
                            'heb_sexe'         => 'required',
                            'heb_telephone'    => 'required|string',
                            'heb_quartiers_id' => 'required',
                            'heb_avenue_rue'   => 'required|string',
                            'heb_numero_adresse' => 'required|string',
                        ]);

                        $hebergeur = Hebergeur::create([
                            'code_hebergeur'       => Hebergeur::genererCode(),
                            'nom'                  => strtoupper($request->heb_nom),
                            'prenom'               => ucfirst(strtolower($request->heb_prenom)),
                            'sexe'                 => $request->heb_sexe,
                            'date_naissance'       => $request->heb_date_naissance,
                            'lieu_naissance'       => $request->heb_lieu_naissance,
                            'nationalite'          => 'Congolaise',
                            'telephone'            => $request->heb_telephone,
                            'email'                => $request->heb_email,
                            'quartiers_id'         => $request->heb_quartiers_id,
                            'avenue_rue'           => $request->heb_avenue_rue,
                            'numero_adresse'       => $request->heb_numero_adresse,
                            'type_piece'           => $request->heb_type_piece,
                            'numero_piece'         => $request->heb_numero_piece,
                            'date_emission_piece'  => $request->heb_date_emission_piece,
                            'date_expiration_piece'=> $request->heb_date_expiration_piece,
                            'profession'           => $request->heb_profession,
                            'photo'                => $request->hasFile('heb_photo')
                                                      ? $request->file('heb_photo')->store('hebergeurs', 'public')
                                                      : null,
                            'created_by'           => Auth::id(),
                        ]);
                        $hebergeurId = $hebergeur->id;
                    }
                    break;

                case 'Etranger':
                    if ($request->filled('hebergeur_impetrant_id')) {
                        // Impétrant existant
                        $hebergeurId = $request->hebergeur_impetrant_id;
                        $impetrant   = Impetrant::find($hebergeurId);
                        if ($impetrant && !$impetrant->est_hebergeur) {
                            $impetrant->est_hebergeur  = 1;
                            $impetrant->code_hebergeur = Hebergeur::genererCode();
                            $impetrant->save();
                        }
                    } else {
                        // Créer un nouvel impétrant hébergeur étranger
                        $request->validate([
                            'heb_etr_nom'            => 'required|string',
                            'heb_etr_prenom'         => 'required|string',
                            'heb_etr_sexe'           => 'required',
                            'heb_etr_date_naissance' => 'required|date',
                            'heb_etr_nationalites_id'=> 'required|exists:pays,id',
                        ]);

                        $uniqueString = strtoupper($request->heb_etr_nom)
                            . strtoupper($request->heb_etr_prenom)
                            . strtoupper($request->heb_etr_sexe)
                            . $request->heb_etr_date_naissance
                            . $request->heb_etr_nationalites_id;

                        $impetrantExistant = Impetrant::where('unique_string', $uniqueString)->first();

                        if ($impetrantExistant) {
                            $hebergeurId = $impetrantExistant->id;
                            if (!$impetrantExistant->est_hebergeur) {
                                $impetrantExistant->est_hebergeur  = 1;
                                $impetrantExistant->code_hebergeur = Hebergeur::genererCode();
                                $impetrantExistant->save();
                            }
                        } else {
                            $nouvelImpetrant = Impetrant::create([
                                'nom'             => strtoupper($request->heb_etr_nom),
                                'prenom'          => ucfirst(strtolower($request->heb_etr_prenom)),
                                'sexe'            => $request->heb_etr_sexe,
                                'date_naissance'  => $request->heb_etr_date_naissance,
                                'lieu_naissance'  => $request->heb_etr_lieu_naissance,
                                'nationalites_id' => $request->heb_etr_nationalites_id,
                                'profession'      => $request->heb_etr_profession,
                                'nom_pere'        => $request->heb_etr_nom_pere,
                                'prenom_pere'     => $request->heb_etr_prenom_pere,
                                'nom_mere'        => $request->heb_etr_nom_mere,
                                'prenom_mere'     => $request->heb_etr_prenom_mere,
                                'unique_string'   => $uniqueString,
                                'est_hebergeur'   => 1,
                                'code_hebergeur'  => Hebergeur::genererCode(),
                                'photo'           => $request->hasFile('heb_etr_photo')
                                                     ? $request->file('heb_etr_photo')->store('demandes', 'public')
                                                     : null,
                            ]);
                            $hebergeurId = $nouvelImpetrant->id;
                        }
                    }
                    break;

                case 'Societe':
                    if ($request->filled('hebergeur_employeur_id')) {
                        // Société existante
                        $hebergeurId = $request->hebergeur_employeur_id;
                        $employeur   = Employeur::find($hebergeurId);
                        if ($employeur && !$employeur->est_hebergeur) {
                            $employeur->est_hebergeur  = 1;
                            $employeur->code_hebergeur = Hebergeur::genererCode();
                            $employeur->save();
                        }
                    } else {
                        // Créer une nouvelle société hébergeuse
                        $request->validate([
                            'heb_soc_nom'      => 'required|string',
                            'heb_soc_telephone'=> 'required|string',
                            'heb_soc_adresse'  => 'required|string',
                        ]);

                        $nouvelEmployeur = Employeur::create([
                            'nom_employeur'   => strtoupper($request->heb_soc_nom),
                            'telephone'       => $request->heb_soc_telephone,
                            'email'           => $request->heb_soc_email,
                            'type'            => $request->heb_soc_type ?? 'Entreprise',
                            'adresse_physique'=> $request->heb_soc_adresse,
                            'registre'        => $request->heb_soc_registre,
                            'est_hebergeur'   => 1,
                            'code_hebergeur'  => Hebergeur::genererCode(),
                        ]);
                        $hebergeurId = $nouvelEmployeur->id;
                    }
                    break;
            }

            // ── ÉTAPE 2 : Résoudre l'hébergé (impétrant) ────────────────────
            $hebergeImpetrantId = null;

            if ($request->filled('heberge_impetrant_id')) {
                // Impétrant existant dans la base
                $hebergeImpetrantId = $request->heberge_impetrant_id;

            } elseif ($request->filled('heberge_nom')) {
                // Créer un nouvel impétrant sans demande
                $request->validate([
                    'heberge_nom'          => 'required|string',
                    'heberge_prenom'       => 'required|string',
                    'heberge_sexe'         => 'required',
                    'heberge_date_naissance' => 'required|date',
                    'heberge_nationalites_id' => 'required|exists:pays,id',
                ]);

                // Vérifier doublons via unique_string
                $uniqueString = strtoupper($request->heberge_nom)
                    . strtoupper($request->heberge_prenom)
                    . strtoupper($request->heberge_sexe)
                    . $request->heberge_date_naissance
                    . $request->heberge_nationalites_id;

                $impetrantExistant = Impetrant::where('unique_string', $uniqueString)->first();

                if ($impetrantExistant) {
                    $hebergeImpetrantId = $impetrantExistant->id;
                } else {
                    $nouvelImpetrant = Impetrant::create([
                        'nom'             => strtoupper($request->heberge_nom),
                        'prenom'          => ucfirst(strtolower($request->heberge_prenom)),
                        'sexe'            => $request->heberge_sexe,
                        'date_naissance'  => $request->heberge_date_naissance,
                        'lieu_naissance'  => $request->heberge_lieu_naissance,
                        'nationalites_id' => $request->heberge_nationalites_id,
                        'unique_string'   => $uniqueString,
                    ]);
                    $hebergeImpetrantId = $nouvelImpetrant->id;
                }
            }

            // ── ÉTAPE 3 : Créer le certificat ────────────────────────────────
            $duree = Carbon::parse($request->date_arrivee_prevue)
                           ->diffInDays(Carbon::parse($request->date_depart_prevue));

            $certificat = CertificatHebergement::create([
                'numero_certificat'    => CertificatHebergement::genererNumeroCertificat(),
                'hebergeur_type'       => $request->hebergeur_type,
                'hebergeur_id'         => $hebergeurId,
                'heberge_impetrant_id' => $hebergeImpetrantId,
                'demande_id'           => $request->demande_id ?? null,
                'date_arrivee_prevue'  => $request->date_arrivee_prevue,
                'date_depart_prevue'   => $request->date_depart_prevue,
                'duree_sejour_jours'   => $duree,
                'motif_sejour'         => $request->motif_sejour,
                'type_relation'        => $request->type_relation,
                'precision_relation'   => $request->precision_relation,
                'statut'               => 'En attente',
                'date_emission'        => today(),
                'date_expiration'      => Carbon::parse($request->date_depart_prevue)->addDays(30),
                'created_by'           => Auth::id(),
            ]);

            DB::commit();
            toastr()->success("Certificat d'hébergement {$certificat->numero_certificat} créé avec succès");
            return redirect()->route('certificats-hebergement.show', $certificat->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CertificatHebergement store: ' . $e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    // =========================================================================
    // SHOW — Détail d'un certificat
    // =========================================================================
    public function show(string $id)
    {
        $certificat = CertificatHebergement::with([
            'hebergeurCongolais.quartier.arrondissement.departement',
            'hebergeurEtranger',
            'hebergeurSociete',
            'heberge',
            'demande',
            'validateur',
            'createur',
        ])->findOrFail($id);

        return view('admin.certificats-hebergement.show', compact('certificat'));
    }

    // =========================================================================
    // VALIDER — Approuver un certificat
    // =========================================================================
    public function valider(string $id)
    {
        $certificat = CertificatHebergement::findOrFail($id);

        if ($certificat->statut !== 'En attente') {
            toastr()->warning("Ce certificat ne peut pas être validé dans son état actuel");
            return back();
        }

        $certificat->update([
            'statut'    => 'Validé',
            'valide_par'=> Auth::id(),
            'valide_le' => now(),
        ]);

        toastr()->success("Certificat {$certificat->numero_certificat} validé avec succès");
        return back();
    }

    // =========================================================================
    // REJETER — Rejeter un certificat
    // =========================================================================
    public function rejeter(Request $request, string $id)
    {
        $request->validate(['motif_rejet' => 'required|string|min:10']);

        $certificat = CertificatHebergement::findOrFail($id);
        $certificat->update([
            'statut'      => 'Rejeté',
            'motif_rejet' => $request->motif_rejet,
            'valide_par'  => Auth::id(),
            'valide_le'   => now(),
        ]);

        toastr()->warning("Certificat {$certificat->numero_certificat} rejeté");
        return back();
    }

    // =========================================================================
    // IMPRIMER — PDF du certificat
    // =========================================================================
    public function imprimer(string $id)
    {
        $certificat = CertificatHebergement::with([
            'hebergeurCongolais.quartier.arrondissement.departement',
            'hebergeurEtranger',
            'hebergeurSociete',
            'heberge',
            'validateur',
        ])->findOrFail($id);

        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('admin.certificats-hebergement.imprimer', compact('certificat'))->render()
        );
        return $html2pdf->output("certificat_{$certificat->numero_certificat}.pdf");
    }

    // =========================================================================
    // STATISTIQUES
    // =========================================================================
    public function statistiques(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->toDateString());
        $to   = $request->get('to',   now()->toDateString());

        $stats = [
            'total'            => CertificatHebergement::whereBetween('created_at', [$from, $to])->count(),
            'valides'          => CertificatHebergement::where('statut', 'Validé')->whereBetween('created_at', [$from, $to])->count(),
            'en_attente'       => CertificatHebergement::where('statut', 'En attente')->whereBetween('created_at', [$from, $to])->count(),
            'rejetes'          => CertificatHebergement::where('statut', 'Rejeté')->whereBetween('created_at', [$from, $to])->count(),
            'par_type_heberg'  => CertificatHebergement::whereBetween('created_at', [$from, $to])
                ->selectRaw('hebergeur_type, COUNT(*) as total')
                ->groupBy('hebergeur_type')->get(),
            'par_relation'     => CertificatHebergement::whereBetween('created_at', [$from, $to])
                ->selectRaw('type_relation, COUNT(*) as total')
                ->groupBy('type_relation')->get(),
            'par_mois'         => CertificatHebergement::whereBetween('created_at', [$from, $to])
                ->selectRaw("DATE_FORMAT(CONVERT_TZ(created_at,'+00:00','+01:00'), '%Y-%m') as mois, COUNT(*) as total")
                ->groupBy('mois')->orderBy('mois')->get(),
            'hebergeurs_actifs'=> [
                'congolais' => Hebergeur::count(),
                'etrangers' => Impetrant::where('est_hebergeur', 1)->count(),
                'societes'  => Employeur::where('est_hebergeur', 1)->count(),
            ],
        ];

        return view('admin.certificats-hebergement.statistiques', compact('stats', 'from', 'to'));
    }

    // =========================================================================
    // DESTROY — Supprimer (soft delete)
    // =========================================================================
    public function destroy(string $id)
    {
        $certificat = CertificatHebergement::findOrFail($id);

        if ($certificat->statut === 'Validé') {
            toastr()->error("Impossible de supprimer un certificat validé");
            return back();
        }

        $certificat->delete();
        toastr()->success("Certificat supprimé avec succès");
        return redirect()->route('certificats-hebergement.index');
    }

    // =========================================================================
    // API — Recherche hébergeur par code
    // =========================================================================
    public function apiRechercherParCode(Request $request)
    {
        $code = strtoupper(trim($request->get('code', '')));

        if (strlen($code) < 3) {
            return response()->json(['found' => false]);
        }

        // Chercher dans les 3 tables
        $congolais = Hebergeur::where('code_hebergeur', $code)->first();
        if ($congolais) {
            return response()->json([
                'found'   => true,
                'type'    => 'Congolais',
                'id'      => $congolais->id,
                'code'    => $congolais->code_hebergeur,
                'nom'     => strtoupper($congolais->nom),
                'prenom'  => $congolais->prenom,
                'telephone' => $congolais->telephone,
                'email'   => $congolais->email,
                'nb_certificats' => $congolais->certificats()->count(),
            ]);
        }

        $etranger = Impetrant::where('code_hebergeur', $code)->where('est_hebergeur', 1)->first();
        if ($etranger) {
            return response()->json([
                'found'   => true,
                'type'    => 'Etranger',
                'id'      => $etranger->id,
                'code'    => $etranger->code_hebergeur,
                'nom'     => strtoupper($etranger->nom),
                'prenom'  => $etranger->prenom,
                'telephone' => '',
                'nb_certificats' => CertificatHebergement::where('hebergeur_type', 'Etranger')->where('hebergeur_id', $etranger->id)->count(),
            ]);
        }

        $societe = Employeur::where('code_hebergeur', $code)->where('est_hebergeur', 1)->first();
        if ($societe) {
            return response()->json([
                'found'   => true,
                'type'    => 'Societe',
                'id'      => $societe->id,
                'code'    => $societe->code_hebergeur,
                'nom'     => $societe->nom_employeur,
                'prenom'  => '',
                'telephone' => $societe->telephone ?? '',
                'nb_certificats' => CertificatHebergement::where('hebergeur_type', 'Societe')->where('hebergeur_id', $societe->id)->count(),
            ]);
        }

        return response()->json(['found' => false]);
    }

    // =========================================================================
    // API — Recherche hébergeur par nom/téléphone
    // =========================================================================
    public function apiRechercherHebergeur(Request $request)
    {
        $q    = trim($request->get('q', ''));
        $type = $request->get('type', 'Congolais');

        if (strlen($q) < 2) return response()->json([]);

        switch ($type) {
            case 'Congolais':
                $results = Hebergeur::recherche($q)->limit(10)->get()->map(fn($h) => [
                    'id'    => $h->id,
                    'code'  => $h->code_hebergeur,
                    'label' => strtoupper($h->nom) . ' ' . $h->prenom . ' — ' . $h->telephone,
                    'type'  => 'Congolais',
                ]);
                break;

            case 'Etranger':
                // Recherche multi-critères : nom, prénom, date naissance, nationalité
                $query = Impetrant::query();

                // Nom (obligatoire si renseigné)
                if ($request->filled('nom')) {
                    $query->where('nom', 'like', $request->nom . '%');
                }
                // Prénom
                if ($request->filled('prenom')) {
                    $query->where('prenom', 'like', $request->prenom . '%');
                }
                // Date de naissance
                if ($request->filled('date_naissance')) {
                    $query->where('date_naissance', $request->date_naissance);
                }
                // Nationalité
                if ($request->filled('nationalites_id')) {
                    $query->where('nationalites_id', $request->nationalites_id);
                }
                // Fallback : si aucun critère précis, chercher par q global
                if (!$request->filled('nom') && !$request->filled('prenom')) {
                    $query->where(function($r) use ($q) {
                        $r->where('nom',    'like', "%{$q}%")
                          ->orWhere('prenom','like', "%{$q}%");
                    });
                }

                $results = $query->with('pays')->limit(15)->get()->map(fn($i) => [
                    'id'          => $i->id,
                    'code'        => $i->code_hebergeur ?? '',
                    'label'       => strtoupper($i->nom) . ' ' . $i->prenom
                                     . ' — ' . ($i->date_naissance
                                         ? \Carbon\Carbon::parse($i->date_naissance)->format('d/m/Y')
                                         : '—')
                                     . ' — ' . ($i->pays?->lib_pays ?? '—')
                                     . ($i->est_hebergeur ? ' ★' : ''),
                    'nom'         => strtoupper($i->nom),
                    'prenom'      => $i->prenom,
                    'dn'          => $i->date_naissance
                                     ? \Carbon\Carbon::parse($i->date_naissance)->format('d/m/Y')
                                     : '—',
                    'nationalite' => $i->pays?->lib_pays ?? '—',
                    'type'        => 'Etranger',
                    'est_heb'     => (bool) $i->est_hebergeur,
                ]);
                break;

            case 'Societe':
                // Chercher TOUS les employeurs (pas seulement est_hebergeur=1)
                $results = Employeur::where('nom_employeur', 'like', "%{$q}%")
                    ->limit(10)->get()->map(fn($e) => [
                        'id'      => $e->id,
                        'code'    => $e->code_hebergeur ?? '',
                        'label'   => $e->nom_employeur
                                     . ($e->est_hebergeur ? ' ★' : ''),
                        'type'    => 'Societe',
                        'est_heb' => (bool) $e->est_hebergeur,
                    ]);
                break;

            default:
                $results = collect([]);
        }

        return response()->json($results);
    }
// =========================================================================
    // RELATIONS — Qui a invité qui
    // =========================================================================
    public function relations(Request $request)
    {
        $query = CertificatHebergement::with([
            'hebergeurCongolais.quartier',
            'hebergeurEtranger.pays',
            'hebergeurSociete',
            'heberge.pays',
            'createur',
        ])->orderByDesc('created_at');
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('numero_certificat', 'like', "%{$s}%")
                  ->orWhereHas('hebergeurCongolais', fn($r) =>
                        $r->where('nom','like',"%{$s}%")
                          ->orWhere('prenom','like',"%{$s}%")
                          ->orWhere('code_hebergeur', $s))
                  ->orWhereHas('hebergeurEtranger', fn($r) =>
                        $r->where('nom','like',"%{$s}%")
                          ->orWhere('prenom','like',"%{$s}%"))
                  ->orWhereHas('hebergeurSociete', fn($r) =>
                        $r->where('nom_employeur','like',"%{$s}%"))
                  ->orWhereHas('heberge', fn($r) =>
                        $r->where('nom','like',"%{$s}%")
                          ->orWhere('prenom','like',"%{$s}%"));
            });
        }
 
        if ($request->filled('type')) {
            $query->where('hebergeur_type', $request->type);
        }
 
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
 
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
 
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
 
        // Export CSV
        if ($request->get('export') === 'csv') {
            return self::exportRelationsCSV($query->get());
        }
 
        $certificats = $query->paginate(20)->withQueryString();
 
        return view('admin.certificats-hebergement.relations', compact('certificats'));
    }
 
    private static function exportRelationsCSV($certificats)
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="relations_hebergement_'.date('Ymd').'.csv"',
        ];
 
        $callback = function() use ($certificats) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
 
            fputcsv($file, [
                'N° Certificat','Statut','Type hébergeur','Hébergeur','Code hébergeur',
                'Hébergé','Nationalité hébergé','Relation',
                'Arrivée prévue','Départ prévu','Durée (jours)','Date émission'
            ], ';');
 
            foreach ($certificats as $cert) {
                fputcsv($file, [
                    $cert->numero_certificat,
                    $cert->statut,
                    $cert->hebergeur_type,
                    $cert->nom_hebergeur,
                    $cert->code_hebergeur,
                    $cert->heberge ? strtoupper($cert->heberge->nom).' '.$cert->heberge->prenom : '',
                    $cert->heberge?->pays?->lib_pays ?? '',
                    $cert->type_relation.($cert->precision_relation ? ' ('.$cert->precision_relation.')' : ''),
                    $cert->date_arrivee_prevue?->format('d/m/Y') ?? '',
                    $cert->date_depart_prevue?->format('d/m/Y') ?? '',
                    $cert->duree_sejour_jours ?? '',
                    $cert->date_emission?->format('d/m/Y') ?? '',
                ], ';');
            }
 
            fclose($file);
        };
 
        return response()->stream($callback, 200, $headers);
    }
 
    // =========================================================================
    // STATISTIQUES AVANCÉES
    // =========================================================================
    public function statistiquesAvancees(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->toDateString());
        $to   = $request->get('to',   now()->toDateString());
 
        $base = CertificatHebergement::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59']);
 
        // ── KPIs principaux ──────────────────────────────────────────────────
        $stats = [
            'total'       => (clone $base)->count(),
            'valides'     => (clone $base)->where('statut','Validé')->count(),
            'en_attente'  => (clone $base)->where('statut','En attente')->count(),
            'rejetes'     => (clone $base)->where('statut','Rejeté')->count(),
            'expires'     => (clone $base)->where('statut','Expiré')->count(),
            'duree_moyenne'=> (clone $base)->avg('duree_sejour_jours') ?? 0,
            'duree_min'   => (clone $base)->min('duree_sejour_jours'),
            'duree_max'   => (clone $base)->max('duree_sejour_jours'),
        ];
 
        // Médiane durée
        $durees = (clone $base)->whereNotNull('duree_sejour_jours')
                               ->orderBy('duree_sejour_jours')
                               ->pluck('duree_sejour_jours');
        $count = $durees->count();
        $stats['duree_mediane'] = $count > 0
            ? ($count % 2 === 0
                ? ($durees[$count/2 - 1] + $durees[$count/2]) / 2
                : $durees[(int)($count/2)])
            : 0;
 
        // Répartition durées
        $stats['durees'] = [
            'court'     => (clone $base)->where('duree_sejour_jours', '<=', 7)->count(),
            'moyen'     => (clone $base)->whereBetween('duree_sejour_jours', [8, 30])->count(),
            'long'      => (clone $base)->whereBetween('duree_sejour_jours', [31, 90])->count(),
            'tres_long' => (clone $base)->where('duree_sejour_jours', '>', 90)->count(),
        ];
 
        // ── Hébergeurs actifs ────────────────────────────────────────────────
        $stats['hebergeurs'] = [
            'congolais' => \App\Models\Hebergeur::count(),
            'etrangers' => \App\Models\Impetrant::where('est_hebergeur', 1)->count(),
            'societes'  => \App\Models\Employeur::where('est_hebergeur', 1)->count(),
        ];
 
        // ── Évolution mensuelle ──────────────────────────────────────────────
        $stats['par_mois'] = \DB::select("
            SELECT
                DATE_FORMAT(CONVERT_TZ(created_at,'+00:00','+01:00'), '%Y-%m') AS mois,
                COUNT(*) AS total,
                SUM(CASE WHEN statut = 'Validé' THEN 1 ELSE 0 END) AS valides
            FROM certificats_hebergement
            WHERE created_at BETWEEN ? AND ?
              AND deleted_at IS NULL
            GROUP BY mois
            ORDER BY mois
        ", [$from.' 00:00:00', $to.' 23:59:59']);
        $stats['par_mois'] = collect($stats['par_mois']);
 
        // ── Par type hébergeur ───────────────────────────────────────────────
        $stats['par_type'] = (clone $base)
            ->selectRaw('hebergeur_type, COUNT(*) as total')
            ->groupBy('hebergeur_type')->get();
 
        // ── Par relation ─────────────────────────────────────────────────────
        $stats['par_relation'] = (clone $base)
            ->selectRaw('type_relation, COUNT(*) as total')
            ->groupBy('type_relation')->get();
 
        // ── Top nationalités hébergées ───────────────────────────────────────
        $stats['nat_hebergees'] = \DB::select("
            SELECT p.lib_pays AS nationalite, p.code, COUNT(*) AS total
            FROM certificats_hebergement c
            JOIN impetrants i ON i.id = c.heberge_impetrant_id
            JOIN pays p ON p.id = i.nationalites_id
            WHERE c.created_at BETWEEN ? AND ?
              AND c.deleted_at IS NULL
            GROUP BY p.id, p.lib_pays, p.code
            ORDER BY total DESC
            LIMIT 10
        ", [$from.' 00:00:00', $to.' 23:59:59']);
        $stats['nat_hebergees'] = collect($stats['nat_hebergees']);
 
        // ── Top nationalités hébergeuses (étrangers seulement) ───────────────
        $stats['nat_hebergeurs'] = \DB::select("
            SELECT p.lib_pays AS nationalite, p.code, COUNT(*) AS total
            FROM certificats_hebergement c
            JOIN impetrants i ON i.id = c.hebergeur_id AND c.hebergeur_type = 'Etranger'
            JOIN pays p ON p.id = i.nationalites_id
            WHERE c.created_at BETWEEN ? AND ?
              AND c.deleted_at IS NULL
            GROUP BY p.id, p.lib_pays, p.code
            ORDER BY total DESC
            LIMIT 10
        ", [$from.' 00:00:00', $to.' 23:59:59']);
        $stats['nat_hebergeurs'] = collect($stats['nat_hebergeurs']);
 
        // ── Top hébergeurs actifs ────────────────────────────────────────────
        $stats['top_hebergeurs'] = \DB::select("
            SELECT
                hebergeur_type,
                hebergeur_id,
                COUNT(*) AS total,
                CASE
                    WHEN hebergeur_type = 'Congolais' THEN
                        (SELECT CONCAT(h.nom,' ',h.prenom) FROM hebergeurs h WHERE h.id = hebergeur_id)
                    WHEN hebergeur_type = 'Etranger' THEN
                        (SELECT CONCAT(i.nom,' ',i.prenom) FROM impetrants i WHERE i.id = hebergeur_id)
                    WHEN hebergeur_type = 'Societe' THEN
                        (SELECT e.nom_employeur FROM employeurs e WHERE e.id = hebergeur_id)
                END AS nom_hebergeur,
                CASE
                    WHEN hebergeur_type = 'Congolais' THEN
                        (SELECT h.code_hebergeur FROM hebergeurs h WHERE h.id = hebergeur_id)
                    WHEN hebergeur_type = 'Etranger' THEN
                        (SELECT i.code_hebergeur FROM impetrants i WHERE i.id = hebergeur_id)
                    WHEN hebergeur_type = 'Societe' THEN
                        (SELECT e.code_hebergeur FROM employeurs e WHERE e.id = hebergeur_id)
                END AS code_hebergeur
            FROM certificats_hebergement
            WHERE created_at BETWEEN ? AND ?
              AND deleted_at IS NULL
            GROUP BY hebergeur_type, hebergeur_id
            ORDER BY total DESC
            LIMIT 10
        ", [$from.' 00:00:00', $to.' 23:59:59']);
        $stats['top_hebergeurs'] = collect($stats['top_hebergeurs']);
 
        // ── Par agent ────────────────────────────────────────────────────────
        $stats['par_agent'] = \DB::select("
            SELECT
                CONCAT(u.prenom, ' ', u.nom) AS agent,
                COUNT(*) AS total,
                SUM(CASE WHEN c.statut = 'Validé' THEN 1 ELSE 0 END) AS valides,
                SUM(CASE WHEN c.statut = 'En attente' THEN 1 ELSE 0 END) AS en_attente
            FROM certificats_hebergement c
            JOIN users u ON u.id = c.created_by
            WHERE c.created_at BETWEEN ? AND ?
              AND c.deleted_at IS NULL
            GROUP BY u.id, agent
            ORDER BY total DESC
            LIMIT 10
        ", [$from.' 00:00:00', $to.' 23:59:59']);
        $stats['par_agent'] = collect($stats['par_agent']);
 
        // ── Par jour de la semaine ────────────────────────────────────────────
        $jours = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        $stats['par_jour'] = \DB::select("
            SELECT
                DAYOFWEEK(CONVERT_TZ(created_at,'+00:00','+01:00')) AS jour_num,
                COUNT(*) AS total
            FROM certificats_hebergement
            WHERE created_at BETWEEN ? AND ?
              AND deleted_at IS NULL
            GROUP BY jour_num
            ORDER BY jour_num
        ", [$from.' 00:00:00', $to.' 23:59:59']);
        $stats['par_jour'] = collect($stats['par_jour'])->map(function($r) use ($jours) {
            $r->jour_label = $jours[$r->jour_num - 1] ?? 'Inconnu';
            return $r;
        });
 
        return view('admin.certificats-hebergement.statistiques_avancees',
                    compact('stats', 'from', 'to'));
    }
 
    // =========================================================================
    // API — Recherche impétrant hébergé
    // =========================================================================
    public function apiRechercherHeberge(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $results = Impetrant::where(function($r) use ($q) {
                $r->where('nom', 'like', "%{$q}%")
                  ->orWhere('prenom', 'like', "%{$q}%");
            })
            ->with('pays')
            ->limit(10)->get()
            ->map(fn($i) => [
                'id'             => $i->id,
                'nom'            => strtoupper($i->nom),
                'prenom'         => $i->prenom,
                'date_naissance' => $i->date_naissance
                    ? Carbon::parse($i->date_naissance)->format('d/m/Y') : '',
                'nationalite'    => $i->pays?->lib_pays ?? '',
                'label'          => strtoupper($i->nom) . ' ' . $i->prenom
                    . ' — ' . ($i->date_naissance ? Carbon::parse($i->date_naissance)->format('d/m/Y') : ''),
            ]);

        return response()->json($results);
    }

    // =========================================================================
    // API — Vérifier numéro de certificat (depuis newcrt/newvisa)
    // =========================================================================
    public function apiVerifierCertificat(Request $request)
    {
        $numero = strtoupper(trim($request->get('numero', '')));

        $certificat = CertificatHebergement::with([
            'hebergeurCongolais', 'hebergeurEtranger', 'hebergeurSociete', 'heberge'
        ])->where('numero_certificat', $numero)->first();

        if (!$certificat) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'               => true,
            'id'                  => $certificat->id,
            'numero'              => $certificat->numero_certificat,
            'statut'              => $certificat->statut,
            'nom_hebergeur'       => $certificat->nom_hebergeur,
            'code_hebergeur'      => $certificat->code_hebergeur,
            'heberge_id'          => $certificat->heberge_impetrant_id,
            // Données affichage
            'heberge_nom'         => $certificat->heberge ? strtoupper($certificat->heberge->nom)   : '',
            'heberge_prenom'      => $certificat->heberge?->prenom   ?? '',
            // Données pour auto-remplissage formulaire
            'heberge_sexe'        => $certificat->heberge?->sexe             ?? '',
            'heberge_dn'          => $certificat->heberge?->date_naissance   ?? '',
            'heberge_lieu'        => $certificat->heberge?->lieu_naissance   ?? '',
            'heberge_nationalites_id' => $certificat->heberge?->nationalites_id ?? '',
            'heberge_nom_pere'    => $certificat->heberge?->nom_pere         ?? '',
            'heberge_prenom_pere' => $certificat->heberge?->prenom_pere      ?? '',
            'heberge_nom_mere'    => $certificat->heberge?->nom_mere         ?? '',
            'heberge_prenom_mere' => $certificat->heberge?->prenom_mere      ?? '',
            // Dates séjour
            'date_arrivee'        => $certificat->date_arrivee_prevue?->format('Y-m-d'),
            'date_depart'         => $certificat->date_depart_prevue?->format('Y-m-d'),
        ]);
    }
}