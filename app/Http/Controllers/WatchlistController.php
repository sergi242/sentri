<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Impetrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pays; // Importation du modèle
use App\Models\Departement;

class WatchlistController extends Controller


{
    public function check($id)
{

$exists = Watchlist::where('impetrant_id',$id)->exists();

return response()->json([
'exists'=>$exists
]);

}
public function getDetailsAjax($id) 
{
    $active = Watchlist::with(['user','user.grade','impetrant'])->findOrFail($id);
        // Pas besoin de ->render(), Laravel le fait automatiquement
    return view('admin.watchlist.partial_details', compact('active'));
}
    /**
     * Liste des profils sous surveillance
     */
    public function index()
    {
        $alerts = Watchlist::with(['impetrant','user.grade', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.watchlist.index', compact('alerts'));
    }

    /**
     * Formulaire de création (Vue optimisée sans chargement massif)
     */
public function create()
{
    // On trie par lib_pays au lieu de nom_fr
    $countries = \App\Models\Pays::orderBy('lib_pays', 'asc')->get();
    $departements = \App\Models\Departement::orderBy('lib_departement', 'asc')->get();

    return view('admin.watchlist.create', compact('countries', 'departements'));
}
    /**
     * Enregistrement du profil (Hybride Recherche/Manuel)
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
    } 
    else {
        $nom = strtoupper($request->nom);
        $prenom = $request->prenom;
        $doc = $request->numero_document;
        $dob = $request->date_naissance;
        $nat = $request->pays_naissance;

        $query = \App\Models\Watchlist::where(function($q) use ($nom, $prenom, $doc, $dob, $nat) {
            if ($doc) {
                $q->where('numero_document', $doc);
            }
            if ($nom && $prenom && $dob && $nat) {
                $q->orWhere(function($sq) use ($nom, $prenom, $dob, $nat) {
                    $sq->where('nom', $nom)
                       ->where('prenom', $prenom)
                       ->where('date_naissance', $dob)
                       ->where('nationalite', $nat);
                });
            }
        });

        if ($query->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ALERTE : Un profil identique existe déjà.');
        }
    }

    // --- PHASE DE CRÉATION ET MAPPING ---

    $watchlist = new \App\Models\Watchlist();
    $watchlist->user_id = \Illuminate\Support\Facades\Auth::id();
    $watchlist->niveau_risque = $request->niveau_risque; 
    $watchlist->motif = $request->motif;
    $watchlist->actif = true;

    if ($request->type_source === 'impetrant') {
        $watchlist->impetrant_id = $request->impetrant_id;

        $imp = \App\Models\Impetrant::find($request->impetrant_id);
        if ($imp) {
            // Infos de base
            $watchlist->nom = $imp->nom;
            $watchlist->prenom = $imp->prenom;
            $watchlist->sexe = $imp->sexe;
            $watchlist->date_naissance = $imp->date_naissance;
            $watchlist->pays_naissance = $imp->lieu_naissance; // Lieu de naissance
            $watchlist->nationalite = $imp->nationalites_id;
            $watchlist->nom_pere = $imp->nom_pere;
            $watchlist->prenom_pere = $imp->prenom_pere;
            $watchlist->nom_mere = $imp->nom_mere;
            $watchlist->prenom_mere = $imp->prenom_mere;

            // Récupération des infos dynamiques sur la DERNIÈRE demande
            $derniereDemande = \App\Models\Demande::where('impetrants_id', $imp->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($derniereDemande) {
                $watchlist->telephone = $derniereDemande->telephone;
                $watchlist->profession = $derniereDemande->profession;
                $watchlist->etat_matrimonial = $derniereDemande->etat_civil;
                $watchlist->photo_profil = $derniereDemande->photo; // On récupère le chemin de la photo
                
                // Adresse construite depuis la demande
                $watchlist->adresse = $derniereDemande->numero_adresse . " " . $derniereDemande->avenue_rue;

                // Numéro de document (Passeport ou autre)
                $docInfo = \App\Models\DocumentDemande::where('demandes_id', $derniereDemande->id)->first();
                if ($docInfo) {
                    $watchlist->numero_document = $docInfo->numero_document;
                }
            }
        }
    } 
    else {
        // Mapping des champs manuels (Inchangé)
        $watchlist->nom = strtoupper($request->nom);
        $watchlist->prenom = $request->prenom;
        $watchlist->sexe = $request->sexe;
        $watchlist->etat_matrimonial = $request->etat_matrimonial;
        $watchlist->numero_document = $request->numero_document;
        $watchlist->telephone = $request->telephone;
        $watchlist->profession = $request->profession;

        if($request->date_naissance) {
            $watchlist->date_naissance = $request->date_naissance;
        } elseif($request->age_min || $request->age_max) {
            $watchlist->age_min = $request->age_min;
            $watchlist->age_max = $request->age_max;
        }

        if($request->pays_naissance) {
            $watchlist->pays_naissance = $request->pays_naissance;
            $watchlist->nationalite = $request->pays_naissance; 
        }

        // Construction adresse manuelle
        $adresseParts = [];
        if($request->numero_domicile) $adresseParts[] = $request->numero_domicile;
        if($request->adresse_rue) $adresseParts[] = $request->adresse_rue;
        if($request->quartier_id){
            $quart = \App\Models\Quartier::find($request->quartier_id);
            if($quart) $adresseParts[] = $quart->lib_quartier;
        }
        $watchlist->adresse = implode(' ', $adresseParts);

        $watchlist->nom_pere = $request->nom_pere;
        $watchlist->prenom_pere = $request->prenom_pere;
        $watchlist->nom_mere = $request->nom_mere;
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
     * Recherche AJAX (Performances pour 18k+ lignes)
     */
    public function searchAjax(Request $request)
    {
        $term = $request->get('q');

        if (empty($term)) {
            return response()->json([]);
        }

        // Recherche optimisée sur nom et prénom
        $results = Impetrant::where('nom', 'LIKE', "%$term%")
            ->orWhere('prenom', 'LIKE', "%$term%")
            ->limit(10) // On limite à 10 pour plus de rapidité
            ->get(['id', 'nom', 'prenom']);

        $formatted = [];
        foreach ($results as $r) {
            $formatted[] = [
                'id' => $r->id, 
                'text' => strtoupper($r->nom) . ' ' . $r->prenom
            ];
        }

        return response()->json($formatted);
    }

    public function destroy(Watchlist $watchlist)
    {
        $watchlist->delete();
        return back()->with('info', 'Le profil a été retiré de la liste de surveillance.');
    }
    /**
 * Récupère les arrondissements (communes) liés à un département
 */
public function getCommunesAjax($departement_id)
{
    // On utilise le modèle Arrondissement selon vos routes existantes
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