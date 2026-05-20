<?php

namespace App\Http\Controllers;

use Exception;
use ZipArchive;
use Carbon\Carbon;
use App\Models\Pays;
use App\Models\Demande;
use App\Rules\FileType;
use Milon\Barcode\DNS2D;
use App\Models\Employeur;
use App\Models\Impetrant;
use App\Models\Contentieux;
use App\Models\Departement;
use App\Models\FicheDemande;
use App\Models\Justificatif;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\FrontiereCongo;
use App\Models\DocumentDemande;
use App\Models\MotifContentieux;
use Ramsey\Uuid\Nonstandard\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ImpetrantNationalite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\TechnoDev\src\Facades\TechnoDev;
use App\Models\CategorieSocioProfessionnelle;
use App\TechnoDev\src\Classes\IdentitySimilarityService;
use App\Models\SimilarityRejection;
    use Barryvdh\DomPDF\Facade\Pdf;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Ajoutez Request $request ici
{
    // 1. On commence par une requête de base
    $query = Demande::query();

    // 2. On vérifie si un filtre "type" est présent dans l'URL (ex: ?type=Visa)
    if ($request->has('type') && $request->type != 'ALL') {
        $query->where('type_demande', $request->type);
    }

    // 3. On pagine ET on ajoute "withQueryString()" pour garder les filtres dans les liens "Suivant"
    $demandes = $query->orderBy("updated_at", "desc")
                      ->paginate(20)
                      ->withQueryString(); 

    $status = "La liste des toutes les demandes d'approbation";
    
    // Gestion du layout (votre code existant reste identique)
    $layout = $request->get("layout");
    if($layout != null){
        $request->session()->put("layout", $layout);
    }

    if(request()->session()->get("layout") == "cards"){
        return view("admin.demandes.cards_list", compact("demandes", "status"));
    } else {
        return view("admin.demandes.index", compact("demandes", "status"));
    }
}

public function generatePdf($id)
{
    // On retire 'commune' et on charge l'arrondissement via le quartier
    $demande = Demande::with(['impetrant', 'quartier.arrondissement'])->findOrFail($id);
    
    $pdf = Pdf::loadView('admin.demandes.impressioncarte_pdf', compact('demande'));
    return $pdf->download('carte_resident_'.$demande->id.'.pdf');
}
public function rejectSimilarity($demandeId, $similaireId)
{
    $demande = Demande::findOrFail($demandeId);
    $similaire = Demande::findOrFail($similaireId);

    $result = IdentitySimilarityService::compare(
        $demande->impetrant,
        $similaire->impetrant
    );

    SimilarityRejection::firstOrCreate([
        'demande_base_id'      => $demandeId,
        'demande_similaire_id' => $similaireId,
        'user_id'              => Auth::id(),
    ]);

    return back()->with('success', 'Similarité écartée.');
}
/**
 * Restaure une similarité précédemment écartée
 */
public function restoreSimilarity($demandeId, $rejectionId)
{
    // On utilise le nom de modèle que je vois à la ligne 65 de ton image
    $rejection = \App\Models\SimilarityRejection::findOrFail($rejectionId);

    // On supprime l'enregistrement pour qu'il ne soit plus "écarté"
    $rejection->delete();

    return back()->with('success', 'Le dossier a été restauré avec succès.');
}
public function rejectedSimilarities($id)
{
    $demande = Demande::with('impetrant')->findOrFail($id);

    $rejections = SimilarityRejection::where(function ($q) use ($id) {
            $q->where('demande_base_id', $id)
              ->orWhere('demande_similaire_id', $id);
        })
        ->with(['baseDemande.impetrant', 'similaireDemande.impetrant'])
        ->latest()
        ->get();

    return view('admin.demandes.similarity_rejected', compact('demande', 'rejections'));
}




 public function approuvees(Request $request) // 1. Injecter la Request
{
    // 2. Préparer la requête de base avec vos conditions existantes
    $query = Demande::where("statut_demande", "Approuvée")
        ->where("updated_at", ">=", Carbon::now()->subDays(30));

    // 3. Appliquer le filtre de type si présent
    if ($request->filled('type') && $request->type != 'ALL') {
        $query->where('type_demande', $request->type);
    }

    // 4. Paginer en conservant les paramètres dans l'URL
    $demandes = $query->orderBy("updated_at", "desc")
        ->paginate(20)
        ->withQueryString(); 

    // Gestion du layout (votre code reste le même)
    $layout = $request->get("layout");
    if ($layout != null) {
        $request->session()->put("layout", $layout);
    }
    
    $status = "La liste des toutes les demandes approuvées";

    if (request()->session()->get("layout") == "cards") {
        return view("admin.demandes.cards_list", compact("demandes", "status"));
    } else {
        return view("admin.demandes.approvee", compact("demandes", "status"));
    }
}
    

    public function impressioncartes()
    {
        $demandes = Demande::where("tag_demande","IMPRESSION")->where("statut_demande","Approuvée")->where("attribue","0")->where("type_demande","Carte de résident temporaire")->paginate(20);
        // return view("admin.demandes.approvee",compact("demandes"));
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        $status = "La liste des toutes les demandes en attente d'impression";
        if(request()->session()->get("layout") =="cards"){
            return view("admin.demandes.cards_list",compact("demandes","status"));
        }else{
            return view("admin.demandes.impressioncarte",compact("demandes","status"));
        }
    }

    public function attente_attribution(){
        $demandes = Demande::where("statut_demande","Approuvée")
        ->where("attribue","0")
        ->where("updated_at", ">=", Carbon::now()->subDays(30))
        ->orderBy("updated_at", "desc")
        ->paginate(20);
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        $status = "La liste des toutes les demandes en attente d'attribution";
        if(request()->session()->get("layout") =="cards"){
            return view("admin.demandes.cards_list",compact("demandes","status"));
        }else{
            return view("admin.demandes.pendingattribution",compact("demandes","status"));
        }
        // return view("admin.demandes.pendingattribution",compact("demandes"));
    }

    public function attribuees(){
        $demandes = Demande::where("attribue","1")
        ->where("updated_at", ">=", Carbon::now()->subDays(30))
        ->orderBy("updated_at", "desc")
        ->paginate(20);
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        $status = "La liste des toutes les demandes attribuées";
        if(request()->session()->get("layout") =="cards"){
            return view("admin.demandes.cards_list",compact("demandes","status"));
        }else{
            return view("admin.demandes.attribuees",compact("demandes","status"));
        }
    }

  public function contentieux(Request $request) // 1. On injecte la Request
{
    // 2. On prépare la base de la requête
    $query = Demande::where("statut_demande", "Envoyée au contentieux");

    // 3. On applique le filtre par type (Visa, CRT, etc.) si présent dans l'URL
    if ($request->filled('type') && $request->type != 'ALL') {
        $query->where('type_demande', $request->type);
    }

    // 4. On ordonne et on pagine en conservant les paramètres (?type=...)
    $demandes = $query->orderBy("updated_at", "desc")
                      ->paginate(20)
                      ->withQueryString(); 

    // Gestion du layout (votre code original)
    $layout = $request->get("layout");
    if($layout != null){
        request()->session()->put("layout", $layout);
    }

    $status = "La liste des toutes les demandes au contentieux";

    if(request()->session()->get("layout") == "cards"){
        return view("admin.demandes.cards_list", compact("demandes", "status"));
    } else {
        return view("admin.demandes.contentieux", compact("demandes", "status"));
    }
}
   public function attentes(Request $request) // 1. Injecter la Request
{
    // 2. Préparer la requête avec vos conditions par défaut
    $query = Demande::where("statut_demande", "En attente d'approbation")
                    ->where("updated_at", ">=", Carbon::now()->subDays(30));

    // 3. Ajouter le filtre de type si l'utilisateur en a choisi un
    if ($request->filled('type') && $request->type != 'ALL') {
        $query->where('type_demande', $request->type);
    }

    // 4. Paginez en utilisant withQueryString() pour ne pas perdre le filtre
    $demandes = $query->orderBy("updated_at", "desc")
                      ->paginate(20)
                      ->withQueryString(); 

    // Gestion du layout (votre code original)
    $layout = $request->get("layout");
    if($layout != null){
        request()->session()->put("layout", $layout);
    }

    $status = "La liste des toutes les demandes en attente d'approbation";

    if(request()->session()->get("layout") == "cards"){
        return view("admin.demandes.cards_list", compact("demandes", "status"));
    } else {
        return view("admin.demandes.attentes", compact("demandes", "status"));
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","3","5"];
        return view("admin.demandes.create",compact("pays","departements","etatsCivils","validites"));
    }

    public function newvisa()
    {
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","3","5"];
        $pieces = Justificatif::all();
        $employeurs = Employeur::all();
        $categories = CategorieSocioProfessionnelle::all();
        return view("admin.demandes.newvisa",compact("pays","departements","etatsCivils","validites","pieces","categories","employeurs"));
    }

    public function newcrt()
    {
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1"];
        $employeurs = Employeur::all();
        $pieces = Justificatif::all();
        $categories = CategorieSocioProfessionnelle::all();
        return view("admin.demandes.newcrt",compact("pays","departements","etatsCivils","validites","pieces","categories","employeurs"));
    }

    public function diplomate()
    {
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","3","5"];
        // $employeurs = Employeur::all();
        $employeurs = Employeur::where("type","Diplomate")->get();
        $pieces = Justificatif::all();
        $categories = CategorieSocioProfessionnelle::all();
        return view("admin.demandes.diplomate",compact("pays","departements","etatsCivils","validites","pieces","categories","employeurs"));
    }

    public function newdocument(){
        return view("admin.demandes.new");
    }

    public function renouvellement(){
        $results = collect([]);
        return view("admin.demandes.renewsearch",compact("results"));
    }

    public function searchdocument(Request $request){
        $request->validate([
            "search_type"=>"string|required",
            "numero_document"=>"string|required"
        ]);

        $type = $request->search_type;

        if($type == "PASSEPORT"){
            $results = DocumentDemande::where("numero_document",$request->numero_document)->get();
            if($results->count() <= 0){
                toastr()->warning("Aucune donnée liée à cette recherche");
            }
            return view("admin.demandes.renewsearch",compact("results"));
        }else if($type == "NUM_FICHE"){
        
            $results = Demande::where("uuid",$request->numero_document)->get();
            if($results->count() <= 0){
                toastr()->warning("Aucune donnée liée à cette recherche");
            }
            return view("admin.demandes.renewsearch",compact("results"));
        }
        else {
            $results = Demande::where("numero_document",$request->numero_document)->get();
            if($results->count() <= 0){
                toastr()->warning("Aucune donnée liée à cette recherche");
            }
            return view("admin.demandes.renewsearch",compact("results"));
        }
    }

    public function renouveler($id){
        $impetrant = Impetrant::find($id);
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","3","5"];
        $categories = CategorieSocioProfessionnelle::all();
        $employeurs = Employeur::all();
        if($impetrant==null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.renouvellement",compact("impetrant","pays","departements","etatsCivils","validites","categories","employeurs"));
    }

    public function renewstore(Request $request,$id)
    {

        $request->validate([
            "quartiers_id"=>"required",
            "avenue_rue"=>"required",
            "numero_adresse"=>"required",
            "telephone"=>"required",
            "numero_passeport"=>"required",
            "date_emission_passeport"=>"required",
            "date_expiration_passeport"=>"required",
            "passeport_delivre_par"=>"required",
            "etat_civil"=>"required|string",
            "type_demande"=>"required|string",
            "validite"=>"required|string",
            "date_demande"=>"required|date",
            "photo"=>["required", new FileType],
            "categorie_socioprofessionnelle_id"=>["required"],
            "tag_demande"=>["required","string"],
            "uuid"=>"required|unique:demandes"
        ]);

        $impetrant = Impetrant::find($id);
        if($impetrant==null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $etatCivil = $request->etat_civil;
        $sexe = $request->sexe;
        $nom_conjoint = $request->nom_conjoint;
        if($sexe == "Féminin" && $etatCivil =="Marié(e)" && $nom_conjoint==""){
            toastr()->warning("Le nom du conjoint est obligatoire tant qu'il s'agit d'une femme mariée");
            return back()->withInput();
        }
        DB::beginTransaction();
        try {
            // Information de l'impétrant
            // Demande
            $demande = new Demande;
            $demande->impetrants_id = $impetrant->id;
            $demande->validite = $request->validite;
            $demande->etat_civil = $request->etat_civil;
            $demande->quartiers_id = $request->quartiers_id;
            $demande->avenue_rue = $request->avenue_rue;
            $demande->numero_adresse = $request->numero_adresse;
            $demande->profession = $request->profession;
            $demande->employeur_id = $request->employeur_id;
            $demande->type_demande = $request->type_demande;
            $demande->date_demande = $request->date_demande;
            $demande->telephone = $request->telephone;
            $demande->nom_conjoint = $request->nom_conjoint;
            $demande->tag_demande = $request->tag_demande;
            $demande->email = $request->email ?? "";
            $demande->categorie_socioprof_id = $request->categorie_socioprofessionnelle_id;
            $demande->created_by = Auth::user()->id;
            $demande->uuid = $request->uuid;
            if($request->hasFile("photo")){
                $ph = $request->file('photo')->store('demandes');
                $demande->photo = $ph;
            }else{
                $demande->photo = "";
            }
            $date_actuelle = Carbon::now();
            $date_expiration = $date_actuelle->addMonths(3);
            $demande->date_validiter_fiche = $date_expiration;
            $demande->save();

            $fiche = new FicheDemande();
            $fiche->demande_id = $demande->id;
            $fiche->date_emission_fiche = $date_actuelle;
            $fiche->date_valite_fiche = $date_expiration;
            $fiche->save();

        // Information des documents
        if($request->numero_passeport &&
            $request->date_emission_passeport &&
            $request->date_expiration_passeport &&
            $request->passeport_delivre_par){
            $passeport = new DocumentDemande;
            $passeport->numero_document = $request->numero_passeport;
            $passeport->date_emission = $request->date_emission_passeport;
            $passeport->date_expiration = $request->date_expiration_passeport;
            $passeport->emis_par = $request->passeport_delivre_par;
            $passeport->demandes_id = $demande->id;
            $passeport->type_document = "Passeport";
            $passeport->save();
        }

        DB::commit();
        toastr()->success("Demande enregistrée avec succès");
        return redirect()->route("demandes.index");
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            DB::rollBack();
            Log::channel("loggin")->error($e->getMessage());
            return back()->withInput();
        }

        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     */
public function checkQuittance(Request $request)
{
    $demande = Demande::where('numero_quittance', $request->numero_quittance)->first();


    if (!$demande) {
        return response()->json([
            'warning' => false
        ]);
    }

    return response()->json([
        'warning' => true,
         'confirmed' => (bool) $demande->quittance_confirmee,
        'message' => 'Cette quittance a déjà été utilisée. Veuillez confirmer avant de continuer.',
        
        'demande' => [
            'nom' => $demande->impetrant->nom,
            'prenom' => $demande->impetrant->prenom,
            'date_naissance' => $demande->impetrant->date_naissance,
            'nationalite' => $demande->impetrant->pays->lib_pays ?? '-',
            'photo' => $demande->photo
                ? asset('app/' . $demande->photo)
                : asset('img/avatar.png'),
            'numero_quittance' => $demande->numero_quittance,
            'date' => $demande->created_at->format('d/m/Y'),
        ]
    ]);
}



public function store(Request $request)
{
    $request->validate([
        "nom" => "required|string",
        "nationalites_id" => "required|exists:pays,id",
        "nationalite_2" => "nullable|exists:pays,id",
        "numero_document" => "required_if:tag_demande,REPRISE|string|nullable",
        "date_attribution" => "required_if:tag_demande,REPRISE|date|nullable",
        "quartiers_id" => "required",
        "sexe" => "required",
        "date_naissance" => "required|date",
        "lieu_naissance" => "required",
        "avenue_rue" => "required",
        "numero_adresse" => "required",
        "telephone" => "required",
        "nom_pere" => "required",
        "nom_mere" => "required",
        "numero_passeport" => "required",
        "date_emission_passeport" => "required|date",
        "date_expiration_passeport" => "required|date",
        "passeport_delivre_par" => "required",
        "etat_civil" => "required|string",
        "type_demande" => "required|string",
        "validite" => "required|string",
        "date_demande" => "required|date",
        "photo" => ["required", new FileType],
        "operation" => ["required"],
        "categorie_socioprofessionnelle_id" => ["required", "exists:categorie_socio_professionnelles,id"],
        "tag_demande" => ["required", "string"],
        "uuid" => "required|unique:demandes"
    ]);

    /*
    |--------------------------------------------------------------------------
    | 1. SCAN INTÉGRAL WATCHLIST (AVANT TRANSACTION)
    |--------------------------------------------------------------------------
    */
    $suspectDetected = false;
    $matchingDetails = [];
    $watchlists = \App\Models\Watchlist::where('actif', true)->get();

    foreach($watchlists as $watch){
        $points = 0;
        $reasons = [];

        if($watch->nom && strtoupper($watch->nom) === strtoupper($request->nom)) { $points += 30; $reasons[] = "Nom identique"; }
        if($watch->prenom && strtolower($watch->prenom) === strtolower($request->prenom)) { $points += 20; $reasons[] = "Prénom identique"; }
        if($watch->numero_document && ($watch->numero_document === $request->numero_passeport || $watch->numero_document === $request->numero_document)) { $points += 50; $reasons[] = "Document identique"; }
        if($watch->telephone && $watch->telephone === $request->telephone) { $points += 30; $reasons[] = "Téléphone identique"; }
        if($watch->nom_pere && strtoupper($watch->nom_pere) === strtoupper($request->nom_pere)) { $points += 15; $reasons[] = "Nom du père identique"; }
        if($watch->nom_mere && strtoupper($watch->nom_mere) === strtoupper($request->nom_mere)) { $points += 15; $reasons[] = "Nom de la mère identique"; }
        if($watch->nationalite && $watch->nationalite == $request->nationalites_id) { $points += 10; $reasons[] = "Nationalité correspondante"; }
        if($watch->profession && strtolower($watch->profession) === strtolower($request->profession)) { $points += 10; $reasons[] = "Profession identique"; }

        if($watch->date_naissance && $watch->date_naissance == $request->date_naissance) {
            $points += 30; $reasons[] = "Date de naissance exacte";
        } elseif($watch->age_min && $watch->age_max) {
            $ageDemande = \Carbon\Carbon::parse($request->date_naissance)->age;
            if($ageDemande >= $watch->age_min && $ageDemande <= $watch->age_max) { $points += 20; $reasons[] = "Dans la tranche d'âge"; }
        }

        if($points >= 60){
            $suspectDetected = true;
            $matchingDetails = array_unique($reasons);
            break; 
        }
    }

    $demandeExistante = Demande::where('numero_quittance', $request->numero_quittance)
        ->where('quittance_confirmee', false)
        ->first();

    if ($demandeExistante && !$request->boolean('force_quittance')) {
        toastr()->error("Quittance déjà utilisée et non confirmée.");
        return back()->withInput();
    }

    DB::beginTransaction();
    try {
        // Information de l'impétrant
        $impretrant = new Impetrant;
        $impretrant->nom = strtoupper($request->nom);
        $impretrant->prenom = ucfirst(strtolower($request->prenom));
        $impretrant->sexe = $request->sexe;
        $impretrant->nationalites_id = $request->nationalites_id;
        $impretrant->date_naissance = $request->date_naissance;
        $impretrant->lieu_naissance = $request->lieu_naissance;
        $impretrant->nom_pere = $request->nom_pere;
        $impretrant->prenom_pere = $request->prenom_pere;
        $impretrant->nom_mere = $request->nom_mere;
        $impretrant->prenom_mere = $request->prenom_mere;
        
        $unique_string = TechnoDev::impetrantUniqueString($impretrant);
        $exists = Impetrant::where("unique_string", $unique_string)->first();

        if ($exists == null) {
            $impretrant->unique_string = $unique_string;
            $impretrant->save();
        } else {
            $impretrant = $exists; 
        }

        $etatCivil = $request->etat_civil;
        $sexe = $request->sexe;
        $nom_conjoint = $request->nom_conjoint;
        if ($sexe == "Féminin" && $etatCivil == "Marié(e)" && $nom_conjoint == "") {
            toastr()->warning("Le nom du conjoint est obligatoire tant qu'il s'agit d'une femme mariée");
            return back()->withInput();
        }

        if ($request->nationalite_id != null) {
            $ina = new ImpetrantNationalite();
            $ina->impetrant_id = $impretrant->id;
            $ina->pays_id = $request->nationalite_id;
            $ina->save();
        }

        if ($request->nationalite_2 != null) {
            $ina = new ImpetrantNationalite();
            $ina->impetrant_id = $impretrant->id;
            $ina->pays_id = $request->nationalite_2;
            $ina->save();
        }

        // Demande
        $demande = new Demande;
        $demande->impetrants_id = $impretrant->id;
        $demande->validite = $request->validite;
        $demande->etat_civil = $request->etat_civil;
        $demande->quartiers_id = $request->quartiers_id;
        $demande->avenue_rue = $request->avenue_rue;
        $demande->numero_adresse = $request->numero_adresse;
        $demande->profession = $request->profession;
        $demande->employeur_id = $request->employeur_id;
        $demande->type_demande = $request->type_demande;
        $demande->date_demande = $request->date_demande;
        $demande->telephone = $request->telephone;
        $demande->email = $request->email;
        $demande->tag_demande = $request->tag_demande;
        $demande->categorie_socioprof_id = $request->categorie_socioprofessionnelle_id;
        $demande->nom_conjoint = $request->nom_conjoint;
        $demande->created_by = Auth::user()->id;
        $demande->uuid = $request->uuid;

        if ($request->hasFile("photo")) {
            $ph = $request->file('photo')->store('demandes');
            $demande->photo = $ph;
        } else {
            $demande->photo = "";
        }

        if ($request->tag_demande == "REPRISE") {
            $demande->statut_demande = "Approuvée";
            $demande->export_json = "";
            $demande->attribue = 1;
            $demande->numero_document = $request->numero_document;
            $date_attr = Carbon::parse($request->date_attribution);
            $demande->date_emission = $date_attr;
            $demande->date_attribution = $date_attr;
            $annees = (int) $request->validite;
            $demande->date_expiration = $date_attr->copy()->addYears($annees);
            $demande->approved_by = 1;
            $demande->approval_date = $date_attr;
        }

        $date_actuelle = Carbon::now();
        $date_expiration_fiche = $date_actuelle->copy()->addMonths(3);
        $demande->date_validiter_fiche = $date_expiration_fiche;
        $demande->numero_quittance = $request->numero_quittance;
        $demande->quittance_confirmee = $request->boolean('force_quittance');
        $demande->save();

        // Fiche de demande
        $fiche = new FicheDemande();
        $fiche->demande_id = $demande->id;
        $fiche->date_emission_fiche = $date_actuelle;
        $fiche->date_valite_fiche = $date_expiration_fiche;
        $fiche->save();

        if ($request->justificatifs != null) {
            $demande->pieces()->sync($request->justificatifs);
        }

        // Document de voyage (Passeport)
        if ($request->numero_passeport != "") {
            $passeport = new DocumentDemande;
            $passeport->numero_document = $request->numero_passeport;
            $passeport->date_emission = $request->date_emission_passeport;
            $passeport->date_expiration = $request->date_expiration_passeport;
            $passeport->emis_par = $request->passeport_delivre_par;
            $passeport->demandes_id = $demande->id;
            $passeport->type_document = "Passeport";
            $passeport->save();
        }

        if ($request->operation == "validate") {
            DB::commit();
            
            // LOGIQUE FINALE : Redirection avec alerte si suspect
            if($suspectDetected) {
                return redirect()->route('demandes.show', $demande->id)
                    ->with('just_created', true)
                    ->with('watchlist_danger', true)
                    ->with('matching_details', $matchingDetails);
            }

            toastr()->success("Demande enregistrée avec succès");
            return redirect()
                ->route('demandes.show', $demande->id)
                ->with('just_created', true);
        } else {
            DB::rollBack(); 
            return self::apercu($request);
        }

    } catch (Exception $e) {
        DB::rollBack();
        toastr()->error($e->getMessage());
        Log::channel("loggin")->error($e->getMessage());
        return back()->withInput();
    }
}

    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $demande = Demande::with('impetrant')->find($id);
    if (!$demande) {
        toastr()->error("Impossible de traiter cette requête");
        return back();
    }

    // --- ÉTAPE A : RÉCUPÉRER LES DOSSIERS DÉJÀ ÉCARTÉS ---
    // On récupère les IDs des suspects que l'utilisateur a déjà choisi d'écarter
    $excludedIds = SimilarityRejection::where('demande_base_id', $id)
                    ->pluck('demande_similaire_id')
                    ->toArray();

    $pays = Pays::all();
    $departements = Departement::all();
    $categories = CategorieSocioProfessionnelle::all();
    $validites = ["1","3","5"];
    $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];

    // --- ÉTAPE B : FILTRER LES SUSPECTS ---
    // On récupère les autres impétrants MAIS on exclut ceux qui sont dans $excludedIds
    $others = Impetrant::where('id', '!=', $demande->impetrants_id)
                ->whereNotIn('id', function($query) use ($excludedIds) {
                    // On s'assure de ne pas charger les impétrants liés aux demandes écartées
                    $query->select('impetrants_id')
                          ->from('demandes')
                          ->whereIn('id', $excludedIds);
                })
                ->get();

    $seuil = env('SIMILARITY_TRESHOLD', 60);
    $similaires = [];

  foreach ($others as $o) {

    $result = TechnoDev::tauxSimilarityDetaille(
        $demande->impetrant,
        $o
    );

    if ($result['score'] >= $seuil) {

        // Récupérer la dernière demande active de cet impétrant
        $otherDemande = $o->demandes()
            ->where('retire', 0)
            ->latest()
            ->first();

        if ($otherDemande) {

            $similaires[] = [
                'demande' => $otherDemande,  // ← IMPORTANT
                'score'   => $result['score'],
                'details' => $result['details'],
                'level'   => $result['level'],
            ];
        }
    }
}



    $sims = collect($similaires);
    $hasSimilarities = $sims->isNotEmpty();

    return view("admin.demandes.show", [
        'demande'         => $demande,
        'pays'            => $pays,
        'departements'    => $departements,
        'validites'       => $validites,
        'etatsCivils'     => $etatsCivils,
        'categories'      => $categories,
        'sims'            => $sims, // Cette variable contiendra 0 élément si tout est écarté
        'hasSimilarities' => $hasSimilarities,
    ]);
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $demande = Demande::find($id);
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","3","5"];
        $pieces = Justificatif::all();
        $employeurs = Employeur::all();
        $categories = CategorieSocioProfessionnelle::all();
        if($demande==null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.newedit",compact("demande","pays","departements","etatsCivils","validites","pieces","employeurs","categories"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $demande = Demande::find($id);
        if($demande==null){
            toastr()->error("Impossible de traiter cette requette");
            return back();
        }

        $request->validate([
            "nom"=>"required|string",
            "prenom"=>"required|string",
        ]);

        DB::beginTransaction();
        try {
            // Information de l'impétrant
            $impretrant = Impetrant::find($demande->impetrants_id);
            $impretrant->nom = $request->nom;
            $impretrant->prenom = $request->prenom;
            $impretrant->sexe = $request->sexe;
            $impretrant->nationalites_id = $request->nationalites_id;
            $impretrant->date_naissance = $request->date_naissance;
            $impretrant->lieu_naissance = $request->lieu_naissance;
            $impretrant->nom_pere = $request->nom_pere;
            $impretrant->prenom_pere = $request->prenom_pere;
            $impretrant->nom_mere = $request->nom_mere;
            $impretrant->prenom_mere = $request->prenom_mere;
            $impretrant->users_id = Auth::user()->id;
            $impretrant->unique_string = TechnoDev::impetrantUniqueString($impretrant);
            
            // query the table
            $imp = Impetrant::where("unique_string",$impretrant->unique_string)->first();
            if($imp == null){
                $impretrant->save();
            }
            $etatCivil = $request->etat_civil;
            $sexe = $request->sexe;
            $nom_conjoint = $request->nom_conjoint;
            if($sexe == "Féminin" && $etatCivil =="Marié(e)" && $nom_conjoint==""){
                toastr()->warning("Le nom du conjoint est obligatoire tant qu'il s'agit d'une femme mariée");
                return back()->withInput();
            }
$existante = Demande::where('numero_quittance', $request->numero_quittance)
    ->where('quittance_confirmee', false)
    ->where('id', '!=', $id)
    ->first();


if (!$request->boolean('force_quittance') && Demande::where('numero_quittance', $request->numero_quittance)->exists()) {
    throw new Exception("Quittance non confirmée");
}


            // Demande
            //$demande->impetrants_id = $impretrant->id;
            $demande->validite = $request->validite;
            $demande->etat_civil = $request->etat_civil;
            $demande->quartiers_id = $request->quartiers_id;
            $demande->avenue_rue = $request->avenue_rue;
            $demande->numero_adresse = $request->numero_adresse;
            $demande->profession = $request->profession;
            $demande->employeur_id = $request->employeur_id;
            $demande->type_demande = $request->type_demande;
            $demande->date_demande = $request->date_demande;
            $demande->telephone = $request->telephone;
            $demande->email = $request->email;
            $demande->tag_demande = $request->tag_demande;
            $demande->nom_conjoint = $request->nom_conjoint;
            $demande->categorie_socioprof_id = $request->categorie_socioprofessionnelle_id;


            if($request->photo != null){
                $ph = $request->photo->store('demandes');
                $demande->photo = $ph;
            }
            $demande->save();

            if($request->justificatifs != null){
                $demande->pieces()->sync($request->justificatifs);
            }


            // Information des documents
            $passeport = DocumentDemande::where("type_document","Passeport")->where("demandes_id",$id)->first();
            if($passeport != null){
                $passeport->numero_document = $request->numero_passeport;
                $passeport->date_emission = $request->date_emission_passeport;
                $passeport->date_expiration = $request->date_expiration_passeport;
                $passeport->emis_par = $request->passeport_delivre_par;
                $passeport->demandes_id = $demande->id;
                $passeport->type_document = "Passeport";
                $passeport->save();
            }

            // Information des documents
            // $carte = DocumentDemande::where("type_document","Carte consulaire")->where("demandes_id",$id)->first();
            // if($carte != null){
            //     $carte->numero_document = $request->numero_carte_consulaire;
            //     $carte->date_emission = $request->date_emission_carte_consulaire;
            //     $carte->date_expiration = $request->date_expiration_carte_consulaire;
            //     $carte->emis_par = $request->carte_delivre_par;
            //     $carte->demandes_id = $demande->id;
            //     $carte->type_document = "Carte consulaire";
            //     $carte->save();
            // }
            DB::commit();
            toastr()->success("Demande modifiée avec succès");
        return redirect()->route("demandes.index");
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            DB::rollBack();
            Log::channel("loggin")->error($e->getMessage());
            return back()->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy(string $id)
{
    $demande = Demande::find($id);

    if (!$demande) {
        toastr()->error("Impossible de traiter cette requête");
        return back();
    }

    $impetrantId = $demande->impetrants_id;

    DB::beginTransaction();
    try {

        // 🔒 Suppression logique
        $demande->retire = 1;
        $demande->retire_le = now();
        $demande->retire_par = Auth::id();
        $demande->save();

        DB::commit();

        toastr()->success("Demande retirée avec succès");
        return redirect()->route('impetrants.demandes', $impetrantId);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        toastr()->error("Erreur lors du retrait");
        return back();
    }
}

public function retirees()
{
    $demandes = Demande::where('retire', 1)
        ->orderBy('retire_le', 'desc')
        ->paginate(20);

    return view('admin.demandes.retirees', compact('demandes'));
}

public function restaurer($id)
{
    $demande = Demande::find($id);

    if (!$demande || !$demande->retire) {
        toastr()->error("Demande introuvable ou non retirée");
        return back();
    }

    DB::beginTransaction();
    try {
        $demande->retire = 0;
        $demande->retire_le = null;
        $demande->retire_par = null;
        $demande->save();

        DB::commit();
        toastr()->success("Demande restaurée avec succès");
        return back();
    } catch (\Exception $e) {
        DB::rollBack();
        toastr()->error("Erreur lors de la restauration");
        return back();
    }
}




    public function takephoto($id){
        $demande = Demande::find($id);
        if($demande == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.prisephoto",compact("demande"));
    }

public function changestate($id, Request $request)
{
    $demande = Demande::with('impetrant')->findOrFail($id);

    if ($request->statut_demande === 'Approuvée') {

        $autresDemandes = Demande::with('impetrant')
            ->where('id', '!=', $demande->id)
            ->get();

        foreach ($autresDemandes as $autreDemande) {

            if (!$autreDemande->impetrant) continue;

            $result = IdentitySimilarityService::compare(
                $demande->impetrant,
                $autreDemande->impetrant
            );

            if (in_array($result['decision'], ['CERTAIN', 'QUASI_CERTAIN'])) {
                toastr()->error(
                    "APPROBATION BLOQUÉE — Identité déjà existante (" .
                    $result['score'] . "%)"
                );
                return back();
            }
        }
    }

    $demande->statut_demande = $request->statut_demande;
    $demande->approved_by = auth()->id();
    $demande->approval_date = now();
    $demande->save();

    toastr()->success("Demande approuvée avec succès");
    return back();
}



    public function createcontentieux($id){
        $demande = Demande::find($id);
        if($demande ==  null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        $motifs = MotifContentieux::all();
        return view("admin.demandes.createcontentieux",compact("demande","motifs"));
    }

    public function storecontentieux($id, Request $request){
        $demande = Demande::find($id);
        if($demande ==  null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        DB::beginTransaction();
        try {
            $contentieux = new Contentieux;
            $contentieux->demandes_id = $id;
            $contentieux->motifs_id = $request->motifs_id;
            $contentieux->description = $request->description;
            $contentieux->save();
            $demande->statut_demande = "Envoyée au contentieux";
            $demande->save();
            DB::commit();

            toastr()->success("Dossier de contentieux créé avec succès");
            return redirect()->route("demandes.show",$id);
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function storeremplirformation($id, Request $request){
        $demande = Demande::find($id);
        if($demande ==  null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        if($demande->statut_demande !=  "Approuvée"){
            toastr()->error("Cette demande n'est pas encore approuvée");
            return back();
        }

        try {
            $demande->numero_document = $request->numero_document;
            $demande->date_emission = $request->date_emission;
            $date_expiration = Carbon::parse($request->date_emission)->addYears((int)$demande->validite)->subDay();
            //dd($date_expiration[0]);
            $demande->date_expiration = $date_expiration;
            $demande->date_attribution = Carbon::now();
            $demande->attribue = 1;
            $demande->attribue_par = Auth::id(); // 👈 L’UTILISATEUR QUI ATTRIBUE
            $demande->save();

            toastr()->success("Information du document ajoutée avec succès");
            return redirect()->route("demandes.show",$id);
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function remplirformation($id){
        $demande = Demande::find($id);
        if($demande ==  null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.renseignedocument",compact("demande"));
    }


    public function fiche($id){
        $demande = Demande::find($id);
        if($demande == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        // if($demande->date_impression_fiche == null){
        //     $date_actuelle = Carbon::now();
        //     $date_expiration = $date_actuelle->addMonths(3);
        //     $demande->date_validiter_fiche = $date_expiration;
        //     $demande->save();
        // }
        // Instancier un objet de la classe DNS2D
        $dns2d = new DNS2D();
    
        // Générer le code QR avec la valeur unique_string de l'impétrant
        $qrCode = $dns2d->getBarcodePNG("rrr", 'QRCODE');
    
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $pdf = 'A';
        if($demande->type_demande == "Visa"){
            $html2pdf->writeHTML(view("admin.demandes.fiche", compact("demande", "qrCode"))->render());
            // $html2pdf->writeHTML(view("admin.demandes.fichevisa", compact("demande", "qrCode"))->render());
        } else {
            $html2pdf->writeHTML(view("admin.demandes.fiche", compact("demande", "qrCode"))->render());
        }
        return $html2pdf->output("$demande->uuid.pdf");
    }
    

    public function apercu($request){
            $impetrant = new Impetrant;
            $impetrant->nom = $request->nom;
            $impetrant->prenom = $request->prenom;
            $impetrant->sexe = $request->sexe;
            $impetrant->nationalites_id = $request->nationalites_id;
            $impetrant->date_naissance = $request->date_naissance;
            $impetrant->lieu_naissance = $request->lieu_naissance;
            $impetrant->nom_pere = $request->nom_pere;
            $impetrant->prenom_pere = $request->prenom_pere;
            $impetrant->nom_mere = $request->nom_mere;
            $impetrant->prenom_mere = $request->prenom_mere;



            $etatCivil = $request->etat_civil;
            $sexe = $request->sexe;
            $nom_conjoint = $request->nom_conjoint;

            // Demande
            $demande = new Demande;
            $demande->validite = $request->validite;
            $demande->etat_civil = $request->etat_civil;
            $demande->quartiers_id = $request->quartiers_id;
            $demande->avenue_rue = $request->avenue_rue;
            $demande->numero_adresse = $request->numero_adresse;
            $demande->profession = $request->profession;
            $demande->employeur = $request->employeur;
            $demande->adresse_employeur = $request->adresse_employeur;
            $demande->type_demande = $request->type_demande;
            $demande->date_demande = $request->date_demande;
            $demande->telephone = $request->telephone;
            $demande->email = $request->email;
            $demande->nom_conjoint = $request->nom_conjoint;




            // Information des documents
            $passeport = new DocumentDemande;
            $passeport->numero_document = $request->numero_passeport;
            $passeport->date_emission = $request->date_emission_passeport;
            $passeport->date_expiration = $request->date_expiration_passeport;
            $passeport->emis_par = $request->passeport_delivre_par;
            $passeport->type_document = "Passeport";


            // Information des documents
            $carte = new DocumentDemande;
            $carte->numero_document = $request->numero_carte_consulaire;
            $carte->date_emission = $request->date_emission_carte_consulaire;
            $carte->date_expiration = $request->date_expiration_carte_consulaire;
            $carte->emis_par = $request->carte_consulaire_delivre_par;
            $carte->type_document = "Carte consulaire";

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            //$html2pdf->setTestTdInOnePage(false);
            $pdf = 'A';
            $html2pdf->writeHTML(view("admin.demandes.apercu",compact("demande","impetrant","passeport","carte"))->render()) ;
            return $html2pdf->output(time()."temp.pdf");
    }

    public function testprint(){
        $fronts = FrontiereCongo::take(5)->get();
        $pays = Pays::whereHas("flux")->get();
        $dtone = request()->get("dtone") ?? Carbon::now();
        $dtwo = request()->get("dtwo") ?? Carbon::now();
        return view("admin.etats.test",compact("fronts","pays","dtone","dtwo"));
    }

    public function demandestats(){
        //$demandes = Demande::whereWeek("date_demande", Carbon::now()->week)->get();
        $critere = request("critere");
        $demandes = collect([]);
        switch ($critere){
            case "jour":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where date(dem.date_demande) =date(curdate())"));
            break;
            case "semaine":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where week(dem.date_demande) =week(curdate()) and year(dem.date_demande)=year(curdate()) "));
                break;
            case "mois":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where month(dem.date_demande) =month(curdate()) and year(dem.date_demande)=year(curdate()) "));
                break;
            case "annee":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where year(dem.date_demande) =year(curdate())"));
                break;
                default:
                $demandes = collect([]);
        }
        return view("admin.demandes.stats",compact("demandes","critere") );
    }

    public function demandesattribuees(){
        //$demandes = Demande::whereWeek("date_demande", Carbon::now()->week)->get();
        $critere = request("critere");
        $demandes = collect([]);
        switch ($critere){
            case "jour":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where date(dem.date_attribution) =date(curdate()) and attribue=1"));
            break;
            case "semaine":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where week(dem.date_attribution) =week(curdate()) and year(dem.date_attribution)=year(curdate()) and attribue=1 "));
                break;
            case "mois":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where month(dem.date_attribution) =month(curdate()) and year(dem.date_attribution)=year(curdate()) and attribue=1"));
                break;
            case "annee":
                $demandes = collect(DB::select("select *  from demandes dem join impetrants imp on dem.impetrants_id = imp.id  where year(dem.date_attribution) =year(curdate()) and attribue=1"));
                break;
                default:
                $demandes = collect([]);
        }
        return view("admin.demandes.statsattributations",compact("demandes","critere") );
    }

    public function search(){
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","2","3","5"];
        $pieces = Justificatif::all();
        $categories = CategorieSocioProfessionnelle::all();
        return view("admin.demandes.search",compact("pays","departements","etatsCivils","pieces","validites","categories"));
    }

public function similarities($id)
{
    $demande = Demande::with('impetrant')->findOrFail($id);
    $imp = $demande->impetrant;

    $SEUIL_AFFICHAGE = 60;

    $query = \App\Models\Impetrant::query()
    ->where('id', '!=', $imp->id)
    /* ->whereHas('demandes', function ($q) {
        $q->where('retire', 0);
    }) */
    ->with(['demandes' => function ($q) {
        $q->where('retire', 0)->orderByDesc('created_at');
    }])
    ->where(function ($q) use ($imp) {
        // Au lieu de "OR" partout, on crée des groupes logiques
        
        // 1. Match par Nom ET Prénom (très probable)
        $q->where(function($sub) use ($imp) {
            $sub->where('nom', 'like', $imp->nom . '%')
                ->where('prenom', 'like', $imp->prenom . '%');
        });

        // 2. OU Match par Nom ET Date de naissance
        if (!empty($imp->date_naissance)) {
            $q->orWhere(function($sub) use ($imp) {
                $sub->where('nom', 'like', $imp->nom . '%')
                    ->where('date_naissance', $imp->date_naissance);
            });
        }
        
        // 3. OU Match exact sur le nom si le prénom est vide
        // (Optionnel : gardez vos orWhere actuels mais augmentez le $SEUIL_AFFICHAGE)
    });

    $sims = [];

    $query->chunk(100, function ($impetrants) use (&$sims, $imp, $SEUIL_AFFICHAGE, $demande) {

        foreach ($impetrants as $potentialMatch) {

            $result = \App\TechnoDev\src\Classes\IdentitySimilarityService::compare(
                $imp,
                $potentialMatch
            );

            $score = $result['score'] ?? 0;

            if ($score < $SEUIL_AFFICHAGE) {
                continue;
            }

            $autreDemande = $potentialMatch->demandes->first();

            if (!$autreDemande) {
                continue;
            }

            // ✅ NOUVEAU : Vérification rejet dans les DEUX SENS
            $isRejected = \App\Models\SimilarityRejection::where(function ($q) use ($demande, $autreDemande) {
                    $q->where('demande_base_id', $demande->id)
                      ->where('demande_similaire_id', $autreDemande->id);
                })
                ->orWhere(function ($q) use ($demande, $autreDemande) {
                    $q->where('demande_base_id', $autreDemande->id)
                      ->where('demande_similaire_id', $demande->id);
                })
                ->exists();

            if ($isRejected) {
                continue; // ⛔ On ignore si rejeté
            }

            $sims[] = [
                'demande'    => $autreDemande,
                'score'      => (int) $score,
                'decision'   => $result['decision'] ?? 'INCONNU',
                'details'    => $result['details'] ?? [],
                'confidence' => $result['confidence'] ?? $score . '%'
            ];
        }
    });

    $sims = collect($sims)
        ->sortBy('score')
        ->values();

    return view('admin.demandes.similarity', compact('demande', 'sims'));
}








    public function takePhotoCamera($id){
        $demande = Demande::find($id);
        if($demande == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.phototest",compact("demande"));
    }

    public function storePhoto(Request $request,$id){

        $demande = Demande::find($id);
        if($demande == null){
            return response()->json([
                "code"=>"180",
                "message"=>"La demande à associer avec la photo n'est pas reconnue"
            ]);
        }

        try {
            $buffer = $request->data;
            $photo = str_replace("data:image/png;base64,"," ",$buffer);
            $uuid = md5($demande->uuid).".".$request->ext;
            $path = public_path("app/demandes/$uuid");
            Storage::disk("demande")->delete($path);
            if(file_put_contents($path,base64_decode($photo))){
                $demande->photo = "demandes/$uuid";
                $demande->save();
                return response()->json([
                    "code"=>"200",
                    "message"=>"Photo prise avec succès"
                ]);
            }else{
                return response()->json([
                    "code"=>"187",
                    "message"=>"Un problème est survenu lors de la prise de la photo"
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "code"=>"187",
                "message"=>$e->getMessage()
            ]);
        }
    }

    public function exportToJson(){
        $results = DB::select("
            SELECT
                impetrants.nom, impetrants.prenom, impetrants.sexe,
                impetrants.date_naissance, impetrants.lieu_naissance,
                categorie_socio_professionnelles.categorie,
                impetrants.nom_pere, impetrants.prenom_pere,
                impetrants.nom_mere, impetrants.prenom_mere,
                pays.lib_pays AS nationalite,
                demandes.photo,
                demandes.id AS demande_id
            FROM impetrants
            INNER JOIN demandes ON demandes.impetrants_id = impetrants.id
            INNER JOIN pays ON impetrants.nationalites_id = pays.id
            INNER JOIN categorie_socio_professionnelles ON demandes.categorie_socioprof_id = categorie_socio_professionnelles.id
            WHERE demandes.export_json != 'exporte'
            AND statut_demande = 'Approuvée'
        ");
        // $results = Demande::where("");
        if($results !=  null){
            $exportData = [];
            $exportFolderName = 'export_' . Carbon::now()->format('Y-m-d_H-i-s');
            $exportFolderPath = storage_path('app/public/' . $exportFolderName);

            // Creation du repertoire
            File::makeDirectory($exportFolderPath, 0755, true);

            foreach ($results as $result) {
                // Construction du chemin de la photo
                $photoPath = public_path('app/' . str_replace('\\', '/', $result->photo));

                if (File::exists($photoPath)) {
                    // Fichier existe, procédez à la copie
                    $newPhotoPath = $exportFolderPath . '/' . basename($result->photo);
                    File::copy($photoPath, $newPhotoPath);

                    // Ajout des données au tableau
                    $exportData[] = [
                        'nom' => $result->nom,
                        'prenom' => $result->prenom,
                        'sexe' => $result->sexe,
                        'date_naissance' => $result->date_naissance,
                        'lieu_naissance' => $result->lieu_naissance,
                        'categorie' => $result->categorie,
                        'nom_pere' => $result->nom_pere,
                        'prenom_pere' => $result->prenom_pere,
                        'nom_mere' => $result->nom_mere,
                        'prenom_mere' => $result->prenom_mere,
                        'nationalite' => $result->nationalite,
                        'photo' => $exportFolderName . '/' . basename($result->photo),
                    ];
                } else {
                    // Fichier n'existe pas, affichez un message d'erreur dans la console
                    // dump("Le fichier n'existe pas : $photoPath");
                }
            }

            // Conversion des données en JSON
            $json = json_encode($exportData, JSON_PRETTY_PRINT);

            // Enregistrement du JSON dans un fichier
            $jsonFilePath = $exportFolderPath . '/exported_data.json';
            file_put_contents($jsonFilePath, $json);

            // Creation du fichier zip
            $zipFileName = $exportFolderName . '.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);
            $zip = new ZipArchive();
            $zip->open($zipFilePath, ZipArchive::CREATE);

            // Ajouter tous les fichiers au zip
            $files = File::allFiles($exportFolderPath);
            foreach ($files as $file) {
                $relativePath = basename($file);
                $zip->addFile($file->getPathname(), $relativePath);
            }

            $zip->close();

            // Obtiens les IDs des demandes dans le résultat
            $demandeIds = collect($results)->pluck('demande_id')->toArray();

            // Met à jour la colonne export_json pour les demandes spécifiques
            Demande::whereIn('id', $demandeIds)->update(['export_json' => 'exporte']);

            // Supprimer le répertoire temporaire
            File::deleteDirectory($exportFolderPath);
            // return response()->json([
            //     'message' => 'Exportation réussie',
            //     'zip_file' => asset('storage/app/public/' . $zipFileName),
            // ]);
            toastr()->success("Données exportées avec succès");
            return back();
        }else{
            toastr()->info("Toutes les données ont déjà été exportées");
            return back();
        }
    }

    public function downloadJson(){
        //récupération des données
        $demandes = Demande::select("type_demande","impetrants_id","uuid","avenue_rue","numero_adresse","quartiers_id")->with(["impetrant:id,nom,prenom,sexe,date_naissance,lieu_naissance,nationalites_id","impetrant.pays:id,lib_pays","quartier:id,lib_quartier"])->where("tag_demande","IMPRESSION")->where("statut_demande","Approuvée")->where("attribue","0")->where("type_demande","Carte de résident temporaire")->get();

    }

    public function renouvelerFiche($id){
        $demande = Demande::find($id);
        if($demande == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        $fiche = FicheDemande::where("demande_id",$demande->id)->get();
        if($fiche->count() > 0){
            if($fiche->count() == 2){
                toastr()->error("Vous ne pouvez pas renouveler la fiche de cette demande");
                return back();
            }else{
                $nf = new FicheDemande();
                $nf->demande_id = $demande->id;
                $nf->date_emission_fiche = $demande->date_validiter_fiche ?? Carbon::parse($demande->date_demande)->addMonths(3);
                $nf->date_valite_fiche = Carbon::parse($demande->date_validiter_fiche)->addMonths(3);
                $nf->save();
                toastr()->success("Fiche renouvelée avec succès");
                return redirect()->route("demandes.show",$demande->id);
            }
        }
        toastr()->error("Impossible de traiter cette requête");
        return back();
    }

    public function procheExpiration(){
        $dateDans3Mois = now()->addMonths(3);
        $demandes = Impetrant::whereHas("demandes",function($query) use ($dateDans3Mois) {
            $query->where("date_expiration","<=",$dateDans3Mois)
            ->where("date_expiration",">=",now());
            
        })->orderBy("nom","asc")->paginate(20);
        return view("admin.impetrants.procheexpiration.index",compact("demandes"));
    }

    public function compareSimilarity(){
        $base = request()->get("base");
        $similar = request()->get("similar");
        $base = Demande::find($base);
        $similar = Demande::find($similar);
        if($base == null || $similar == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.comparaison-similarity",compact("base","similar"));
    }

 public function forceDelete($id)
{
    $demande = Demande::with('impetrant')->find($id);

    if (!$demande) {
        toastr()->error("Demande introuvable");
        return back();
    }

    DB::beginTransaction();
    try {

        $impetrant = $demande->impetrant;

        // Supprimer la demande
        $demande->delete();

        // Vérifier si l'impétrant a encore des demandes
        if ($impetrant && $impetrant->demandes()->count() === 0) {
            $impetrant->delete();
        }

        DB::commit();
        toastr()->success("Demande supprimée définitivement");
        return back();

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e->getMessage());
        toastr()->error("Erreur lors de la suppression définitive");
        return back();
    }
}
public function precheck(Request $request)
{
    $request->validate([
        'type' => 'required|string',
        'numero' => 'required|string',
    ]);

    $results = collect();

    switch ($request->type) {

        case 'PASSEPORT':
            $results = Demande::whereHas('documents', function ($q) use ($request) {
                $q->where('type_document', 'Passeport')
                  ->where('numero_document', $request->numero);
            })->with('impetrant')->get();
            break;

        case 'NUM_FICHE':
            $results = Demande::where('uuid', $request->numero)
                ->with('impetrant')->get();
            break;

        case 'VISA':
        case 'CRT':
            $results = Demande::where('numero_document', $request->numero)
                ->with('impetrant')->get();
            break;
    }

    return response()->json([
        'found' => $results->isNotEmpty(),
        'results' => $results
    ]);
}



}

