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

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $demandes = Demande::where("statut_demande","En attente d'approbation")->orderBy("updated_at","desc")->paginate(20);
        $demandes = Demande::orderBy("updated_at","desc")->paginate(20);
        $status = "La liste des toutes les demandes d'approbation";
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        if(request()->session()->get("layout") =="cards"){
            return view("admin.demandes.cards_list",compact("demandes","status"));
        }else{
            return view("admin.demandes.index",compact("demandes","status"));
        }
    }

    public function approuvees()
    {
        $demandes = Demande::where("statut_demande", "Approuvée")
            ->where("updated_at", ">=", Carbon::now()->subDays(30))
            ->orderBy("updated_at", "desc")
            ->paginate(20); 
    
        $layout = request()->get("layout");
        if ($layout != null) {
            request()->session()->put("layout", $layout);
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

    public function contentieux()
    {
        $demandes = Demande::where("statut_demande","Envoyée au contentieux")->orderBy("updated_at","desc")->paginate(20);
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        $status = "La liste des toutes les demandes au contentieux";
        if(request()->session()->get("layout") =="cards"){
            return view("admin.demandes.cards_list",compact("demandes","status"));
        }else{
            return view("admin.demandes.contentieux",compact("demandes","status"));
        }
        // return view("admin.demandes.contentieux",compact("demandes"));
    }
    public function attentes()
    {
        $demandes = Demande::where("statut_demande","En attente d'approbation")
        ->where("updated_at", ">=", Carbon::now()->subDays(30))
        ->orderBy("updated_at", "desc")
        ->paginate(20);
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        $status = "La liste des toutes les demandes en attente d'approbation";
        if(request()->session()->get("layout") =="cards"){
            return view("admin.demandes.cards_list",compact("demandes","status"));
        }else{
            return view("admin.demandes.attentes",compact("demandes","status"));
        }
        // return view("admin.demandes.attentes",compact("demandes"));
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
        $employeurs = Employeur::all();
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
        }else{
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
     */
    public function store(Request $request)
    {

        $request->validate([
            "nom"=>"required|string",
            "nationalites_id"=>"required|exists:pays,id",
            "nationalite_2"=>"nullable|exists:pays,id",
            "numero_document"=>"required_if:tag_demande,REPRISE|string|nullable",
            "date_attribution"=>"required_if:tag_demande,REPRISE|date|nullable",
            "quartiers_id"=>"required",
            "sexe"=>"required",
            "date_naissance"=>"required|date",
            "lieu_naissance"=>"required",
            "avenue_rue"=>"required",
            "numero_adresse"=>"required",
            "telephone"=>"required",
            "nom_pere"=>"required",
            "nom_mere"=>"required",
            "numero_passeport"=>"required",
            "date_emission_passeport"=>"required|date",
            "date_expiration_passeport"=>"required|date",
            "passeport_delivre_par"=>"required",
            "etat_civil"=>"required|string",
            "type_demande"=>"required|string",
            "validite"=>"required|string",
            "date_demande"=>"required|date",
            "photo"=>["required", new FileType],
            "operation"=>["required"],
            "categorie_socioprofessionnelle_id"=>["required","exists:categorie_socio_professionnelles,id"],
            "tag_demande"=>["required","string"],
            "uuid"=>"required|unique:demandes"
        ]);


        DB::beginTransaction();
        try {
            // Information de l'impétrant
            $impretrant = new Impetrant;
            $impretrant->nom = strtoupper($request->nom); //Ajout du nom en majuscule
            $impretrant->prenom = ucfirst(strtolower($request->prenom)); //Ajout de la premiere lettre du prenom en majuscule
            $impretrant->sexe = $request->sexe;
            $impretrant->nationalites_id = $request->nationalites_id;
            $impretrant->date_naissance = $request->date_naissance;
            $impretrant->lieu_naissance = $request->lieu_naissance;
            $impretrant->nom_pere = $request->nom_pere;
            $impretrant->prenom_pere = $request->prenom_pere;
            $impretrant->nom_mere = $request->nom_mere;
            $impretrant->prenom_mere = $request->prenom_mere;
            $unique_string = TechnoDev::impetrantUniqueString($impretrant);
            $exists = Impetrant::where("unique_string",$unique_string)->first();

            if($exists == null){
                $impretrant->unique_string = TechnoDev::impetrantUniqueString($impretrant);
                $impretrant->save();
            }

            $etatCivil = $request->etat_civil;
            $sexe = $request->sexe;
            $nom_conjoint = $request->nom_conjoint;
            if($sexe == "Féminin" && $etatCivil =="Marié(e)" && $nom_conjoint==""){
                toastr()->warning("Le nom du conjoint est obligatoire tant qu'il s'agit d'une femme mariée");
                return back()->withInput();
            }

            

            if($request->nationalite_id != null){
                $ina = new ImpetrantNationalite();
                $ina->impetrant_id = $impretrant->id;
                $ina->pays_id = $request->nationalite_id;
                $ina->save();
            }

            if($request->nationalite_2 != null){
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
            if($request->hasFile("photo")){
                $ph = $request->file('photo')->store('demandes');
                $demande->photo = $ph;
            }else{
                $demande->photo = "";
            }

            if($request->tag_demande=="REPRISE"){
                $demande->statut_demande = "Approuvée";
                $demande->export_json = "";
            }

            if($request->tag_demande == "REPRISE"){
                $demande->statut_demande = "Approuvée";
                $demande->export_json = "";
                $demande->attribue = 1;
                $demande->numero_document = $request->numero_document;
                $demande->date_emission = $request->date_attribution;
                $demande->date_attribution = $request->date_attribution;
                $demande->approved_by = 1;
                $demande->approval_date = $request->date_attribution;
            }
            //Ajout de la date d'expiration de la fiche + 3 mois par defaut pour les demandes de reprise
            $date_actuelle = Carbon::now();
            $date_expiration = $date_actuelle->addMonths(3);
            $demande->date_validiter_fiche = $date_expiration;

            $demande->save();

            $fiche = new FicheDemande();
            $fiche->demande_id = $demande->id;
            $fiche->date_emission_fiche = $date_actuelle;
            $fiche->date_valite_fiche = $date_expiration;
            $fiche->save();

            //pieces

            if($request->justificatifs != null){
                $demande->pieces()->sync($request->justificatifs);
            }

            if($request->numero_passeport != ""){
                // Information des documents
                $passeport = new DocumentDemande;
                $passeport->numero_document = $request->numero_passeport;
                $passeport->date_emission = $request->date_emission_passeport;
                $passeport->date_expiration = $request->date_expiration_passeport;
                $passeport->emis_par = $request->passeport_delivre_par;
                $passeport->demandes_id = $demande->id;
                $passeport->type_document = "Passeport";
                $passeport->save();
            }

            if($request->operation == "validate"){
                DB::commit();
                toastr()->success("Demande enregistrée avec succès");
                return redirect()->route("demandes.index");
            }else{
                return self::apercu($request);
            }
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            DB::rollBack();
            Log::channel("loggin")->error($e->getMessage());
            return back()->withInput();
        }

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $demande = Demande::find($id);
        $pays = Pays::all();
        $departements = Departement::all();
        $etatsCivils = ['Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)'];
        $validites = ["1","3","5"];
        $categories = CategorieSocioProfessionnelle::all();
        if($demande==null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $others = Impetrant::whereNotIn("unique_string",[$demande->impetrant?->unique_string])->get();

        $similaires = [];

        $seuil = env("SIMILARITY_TRESHOLD",70);

        if($others->count() > 0){
            foreach($others as $o){
                if(TechnoDev::tauxSimilarity($o->unique_string,$demande->impetrant?->unique_string) > $seuil){
                     array_push($similaires,$o);
                }
            }
        }
        $sims = collect($similaires);
        //toastr()->warning("Cet impétrant présente ".$sims->count() ." similarité(s)");
        return view("admin.demandes.show",compact("demande","pays","departements","etatsCivils","validites","sims","categories"));
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
        //
    }

    public function takephoto($id){
        $demande = Demande::find($id);
        if($demande == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.demandes.prisephoto",compact("demande"));
    }

    public function changestate($id, Request $request){
        $demande = Demande::find($id);
        if($demande ==  null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        try {
            if($request->statut_demande == "Approuvée"){
                $demande->approval_date = Carbon::now();
                $demande->approved_by = Auth::user()->id;
            }
            $demande->statut_demande = $request->statut_demande;

            // $demande->approved_by = Auth::user()->id;
            $demande->save();
            toastr()->success("Statut de la demande modifié avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            return back();
        }
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

    public function similarities($id){
        $demande = Demande::find($id);
        if($demande == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $others = Impetrant::whereNotIn("unique_string",[$demande->impetrant?->unique_string])->get();

        $similaires = [];

        $seuil = env("SIMILARITY_TRESHOLD",70);

        if($others->count() > 0){
            foreach($others as $o){
                if(TechnoDev::tauxSimilarity($o->unique_string,$demande->impetrant?->unique_string) > $seuil){
                     array_push($similaires,$o);
                }
            }
        }
        $sims = collect($similaires);

        return view("admin.demandes.similarity",compact("sims","demande"));

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


}
