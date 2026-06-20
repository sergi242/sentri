<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Impetrant;
use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pays;
use App\Models\Departement;

class WatchlistController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function check($id)
    {
        $result = $this->api->checkWatchlist($id);

        // The API returns { exists: bool } or fall back to local if error
        if (!empty($result['error'])) {
            $exists = Watchlist::where('impetrant_id', $id)->exists();
        } else {
            $exists = (bool) ($result['exists'] ?? false);
        }

        return response()->json([
            'exists' => $exists,
        ]);
    }

    public function getDetailsAjax($id)
    {
        $active = Watchlist::with(['user', 'user.grade', 'impetrant'])->findOrFail($id);
        return view('admin.watchlist.partial_details', compact('active'));
    }

    /**
     * Liste des profils sous surveillance
     */
    public function index()
    {
        $response = $this->api->getWatchlist();

        if (!empty($response['error'])) {
            // Graceful degradation: return empty paginator
            $alerts = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        } elseif (isset($response['data']) && isset($response['total'])) {
            // Paginated response from API
            $items  = collect($response['data'])->map(fn($a) => (object) $a);
            $alerts = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $response['total'],
                $response['per_page'] ?? 15,
                $response['current_page'] ?? 1
            );
        } else {
            // Plain array
            $items  = collect($response)->map(fn($a) => (object) $a);
            $alerts = new \Illuminate\Pagination\LengthAwarePaginator($items, $items->count(), 15);
        }

        return view('admin.watchlist.index', compact('alerts'));
    }

    /**
     * Formulaire de création (Vue optimisée sans chargement massif)
     */
    public function create()
    {
        $countries    = \App\Models\Pays::orderBy('lib_pays', 'asc')->get();
        $departements = \App\Models\Departement::orderBy('lib_departement', 'asc')->get();

        return view('admin.watchlist.create', compact('countries', 'departements'));
    }

    /**
     * Enregistrement du profil (Hybride Recherche/Manuel)
     * Garde la logique locale complexe (similarity service, DocumentDemande, etc.)
     */
    public function store(Request $request)
    {
        // 1. Validation initiale des champs obligatoires communs
        $request->validate([
            'type_source'   => 'required|in:impetrant,manuel',
            'niveau_risque' => 'required|integer|min:1|max:3',
            'motif'         => 'required|string',
        ]);

        // --- PHASE DE VÉRIFICATION ANTI-DOUBLON ---

        if ($request->type_source === 'impetrant') {
            $request->validate([
                'impetrant_id' => 'required|exists:impetrants,id'
            ]);

            $exists = \App\Models\Watchlist::where('impetrant_id', $request->impetrant_id)->exists();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Cet individu (Base Centrale) est déjà inscrit dans la Watchlist.');
            }
        } else {
            $nom = strtoupper($request->nom);
            $prenom = $request->prenom;
            $doc = $request->numero_document;
            $dob = $request->date_naissance;
            $nat = $request->pays_naissance;

            if ($doc && \App\Models\Watchlist::where('numero_document', $doc)->exists()) {
                return redirect()->back()->withInput()->with('error', 'ALERTE : Un profil avec ce document existe déjà.');
            }
            $existingEntries = \App\Models\Watchlist::where('actif', true)->get();
            foreach ($existingEntries as $entry) {
                $r = \App\TechnoDev\src\Classes\IdentitySimilarityService::compareWithWatchlist($entry, [
                    'nom' => $nom, 'prenom' => $prenom, 'date_naissance' => $dob,
                    'nationalites_id' => $nat, 'numero_passeport' => $doc ?? '',
                    'telephone' => '', 'nom_pere' => '', 'nom_mere' => '',
                ]);
                if ($r['score'] >= 75) {
                    return redirect()->back()->withInput()
                        ->with('error', "ALERTE : Un profil similaire existe déjà ({$r['score']}%) — {$entry->nom} {$entry->prenom}.");
                }
            }
        }

        // --- PHASE DE CRÉATION ET MAPPING ---

        $watchlist = new \App\Models\Watchlist();
        $watchlist->user_id      = \Illuminate\Support\Facades\Auth::id();
        $watchlist->niveau_risque = $request->niveau_risque;
        $watchlist->motif        = $request->motif;
        $watchlist->actif        = true;

        if ($request->type_source === 'impetrant') {
            $watchlist->impetrant_id = $request->impetrant_id;

            $imp = \App\Models\Impetrant::find($request->impetrant_id);
            if ($imp) {
                $watchlist->nom            = $imp->nom;
                $watchlist->prenom         = $imp->prenom;
                $watchlist->sexe           = $imp->sexe;
                $watchlist->date_naissance = $imp->date_naissance;
                $watchlist->pays_naissance = $imp->lieu_naissance;
                $watchlist->nationalite    = $imp->nationalites_id;
                $watchlist->nom_pere       = $imp->nom_pere;
                $watchlist->prenom_pere    = $imp->prenom_pere;
                $watchlist->nom_mere       = $imp->nom_mere;
                $watchlist->prenom_mere    = $imp->prenom_mere;

                $derniereDemande = \App\Models\Demande::where('impetrants_id', $imp->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($derniereDemande) {
                    $watchlist->telephone        = $derniereDemande->telephone;
                    $watchlist->profession       = $derniereDemande->profession;
                    $watchlist->etat_matrimonial = $derniereDemande->etat_civil;
                    $watchlist->photo_profil     = $derniereDemande->photo;
                    $watchlist->adresse          = $derniereDemande->numero_adresse . " " . $derniereDemande->avenue_rue;

                    $docInfo = \App\Models\DocumentDemande::where('demandes_id', $derniereDemande->id)->first();
                    if ($docInfo) {
                        $watchlist->numero_document = $docInfo->numero_document;
                    }
                }
            }
        } else {
            $watchlist->nom              = strtoupper($request->nom);
            $watchlist->prenom           = $request->prenom;
            $watchlist->sexe             = $request->sexe;
            $watchlist->etat_matrimonial = $request->etat_matrimonial;
            $watchlist->numero_document  = $request->numero_document;
            $watchlist->telephone        = $request->telephone;
            $watchlist->profession       = $request->profession;

            if ($request->date_naissance) {
                $watchlist->date_naissance = $request->date_naissance;
            } elseif ($request->age_min || $request->age_max) {
                $watchlist->age_min = $request->age_min;
                $watchlist->age_max = $request->age_max;
            }

            if ($request->pays_naissance) {
                $watchlist->pays_naissance = $request->pays_naissance;
                $watchlist->nationalite    = $request->pays_naissance;
            }

            $adresseParts = [];
            if ($request->numero_domicile) $adresseParts[] = $request->numero_domicile;
            if ($request->adresse_rue) $adresseParts[] = $request->adresse_rue;
            if ($request->quartier_id) {
                $quart = \App\Models\Quartier::find($request->quartier_id);
                if ($quart) $adresseParts[] = $quart->lib_quartier;
            }
            $watchlist->adresse = implode(' ', $adresseParts);

            $watchlist->nom_pere    = $request->nom_pere;
            $watchlist->prenom_pere = $request->prenom_pere;
            $watchlist->nom_mere    = $request->nom_mere;
            $watchlist->prenom_mere = $request->prenom_mere;

            if ($request->hasFile('photo_profil')) {
                $path = $request->file('photo_profil')->store('watchlist_photos', 'public');
                $watchlist->photo_profil = $path;
            }
        }

        $watchlist->save();

        return redirect()
            ->route('watchlist.index')
            ->with('success', 'Le profil a été inscrit au registre de surveillance avec succès.');
    }

    /**
     * Recherche AJAX — impétrants pour auto-complétion watchlist
     */
    public function searchAjax(Request $request)
    {
        $term = $request->get('q');

        if (empty($term)) {
            return response()->json([]);
        }

        $response = $this->api->getImpetrants(['search' => $term, 'per_page' => 10]);
        $raw      = $response['data'] ?? (isset($response['error']) ? [] : $response);

        $formatted = array_map(fn($r) => [
            'id'   => $r['id'] ?? $r->id ?? null,
            'text' => strtoupper($r['nom'] ?? $r->nom ?? '') . ' ' . ($r['prenom'] ?? $r->prenom ?? ''),
        ], is_array($raw) ? $raw : $raw->toArray());

        return response()->json(array_values($formatted));
    }

    public function destroy(Watchlist $watchlist)
    {
        $result = $this->api->deleteWatchlist($watchlist->id);

        if (!empty($result['error'])) {
            // Fall back to local delete if API fails
            $watchlist->delete();
        }

        return back()->with('info', 'Le profil a été retiré de la liste de surveillance.');
    }

    /**
     * Récupère les arrondissements (communes) liés à un département
     */
    public function getCommunesAjax($departement_id)
    {
        $communes = \App\Models\Arrondissement::where('departement_id', $departement_id)
            ->orderBy('nom', 'asc')
            ->get(['id', 'nom']);

        return response()->json($communes);
    }

    /**
     * Récupère les quartiers liés à un arrondissement
     */
    public function getQuartiersAjax($commune_id)
    {
        $quartiers = \App\Models\Quartier::where('arrondissement_id', $commune_id)
            ->orderBy('nom', 'asc')
            ->get(['id', 'nom']);

        return response()->json($quartiers);
    }
}
