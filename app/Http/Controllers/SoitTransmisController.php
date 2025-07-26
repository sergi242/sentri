<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Demande;
use App\Models\SoitTransmis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoitTransmisController extends Controller
{
    //
    public function index() {
        $soit_transmis = SoitTransmis::withCount('demandes as demandes_count')->orderBy('created_at', 'desc')->get();
        return view("admin.soittransmis.index", compact("soit_transmis"));
    }
    
    public function show($id){
        $soit_transmis = SoitTransmis::find($id);
        $demandes = Demande::where("soit_transmis_id",$id)->get();
        return view("admin.soittransmis.show", compact("soit_transmis","demandes"));
    }

    public function edit($id){
        $soit_transmis = SoitTransmis::find($id);
        $users = User::all();
        return view("admin.soittransmis.edit", compact("soit_transmis","users"));
    }

    public function update(Request $request, $id){
        try {
            $soit_transmis = SoitTransmis::find($id);
            // $soit_transmis->numero = $request->numero;
            $soit_transmis->users_id = $request->users_id;
            $soit_transmis->description = $request->description;
            $soit_transmis->save();
            toastr()->success("Soit-Transmis modifié avec succès");
            return redirect()->route('soit-transmis.edit', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id){
        try {
            $soit_transmis = SoitTransmis::find($id);
            $soit_transmis->delete();
            toastr()->success("Soit-Transmis supprimé avec succès");
            return redirect()->route('soit-transmis.index');
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function create(){
        $users = User::all();
        return view("admin.soittransmis.create", compact("users"));
    }
    
    public function store(Request $request){
        try {
            $request->validate([
                "users_id"=>"required",
            ]);
            $soit_transmis = new SoitTransmis();
            $soit_transmis->numero = $this->generateNumber();
            $soit_transmis->users_id = $request->users_id;
            $soit_transmis->description = $request->description;
            $soit_transmis->commanditaire_id = $request->commanditaire_id;
            $soit_transmis->created_by = auth()->user()->id;
            $soit_transmis->save();
            $id = $soit_transmis->id;
            toastr()->success("Soit-Transmis ajouté avec succès");
            // dd($id);
            return redirect()->route('soit-transmis.demandes.show', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }
    

    // public function showDemandes(Request $request){
    //     $soit_transmis_id = $request->id; //Recupération de l'id du soit-transmis enregistré
    //     $soit_transmis = SoitTransmis::where("id",$soit_transmis_id)->withCount('demandes as demandes_count')->first();
    
    //     $demandes = Demande::where("statut_demande","Approuvée")->whereNull('soit_transmis_id')->orderBy("updated_at","desc")->get(); // ecupération des demandes Approuvées
    //     return view("admin.soittransmis.storedemandes", compact("demandes","soit_transmis"));
    // }
    public function showDemandes(Request $request)
    {
        $soit_transmis_id = $request->id; // Récupération de l'id du soit-transmis enregistré
        $soit_transmis = SoitTransmis::where("id", $soit_transmis_id)->withCount('demandes as demandes_count')->first();

        $demandes = Demande::where("statut_demande", "Approuvée")
            ->whereNull('soit_transmis_id')
            ->where("statut_demande", "!=", "Envoyée au contentieux") // Exclure les demandes en contentieux
            ->orderBy("updated_at", "desc")
            ->get();

        return view("admin.soittransmis.storedemandes", compact("demandes", "soit_transmis"));
    }


    public function storeDemandes(Request $request)
    {
        try {
            $soit_transmis_id = $request->soit_transmis_id;
            $demande_id = $request->demande_id;

            $demande = Demande::find($demande_id);

            // Vérifier si la demande est en contentieux
            if ($demande->statut_demande === "Envoyée au contentieux") {
                toastr()->error("Les demandes envoyées au contentieux ne peuvent pas être ajoutées à un soit transmis.");
                return back()->withInput();
            }

            $demande->soit_transmis_id = $soit_transmis_id;
            $demande->save();

            $id = $soit_transmis_id;

            toastr()->success("Demande ajoutée avec succès");
            return redirect()->route('soit-transmis.demandes.show', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }
    

    public function dropDemandes(Request $request){
        try {
            $demande_id = $request->demande_id;
            $demande = Demande::find($demande_id);
            $id = $demande->soit_transmis_id;
            $demande->soit_transmis_id = null;
            $demande->save();
            toastr()->success("Demande retirée avec succès");
            return redirect()->route('soit-transmis.show', compact('id'));
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function generateNumber() {
        $aujourdHui = now()->format('Y-m-d');
    
        // Rechercher les soit transmis créés aujourd'hui
        $dernierSoitTransmis = SoitTransmis::whereDate('created_at', $aujourdHui)
            ->orderBy('id', 'desc')
            ->first();

        $numeroProduction = null;

        // Si un soit transmis existe pour aujourd'hui, incrémenter le numéro de production
        if ($dernierSoitTransmis) {
            // Extraire les trois derniers chiffres du numéro
            $derniersChiffres = substr($dernierSoitTransmis->numero, -3);
        
            // Convertir en entier et incrémenter
            $numeroProduction = $derniersChiffres + 1;
        
            // Si le numéro de production dépasse 999 
            if ($numeroProduction > 999) {
                toastr()->error("Le nombre de numero de production est supérieur à 999");
                return back()->withInput();
            }
        }
    
        // Formater le numéro de production sur trois chiffres
        $numeroProductionFormate = sprintf('%03d', $numeroProduction);
    
        // Générer le numéro unique
        $annee = substr(date('Y'), 2); // prend les 2 derniers chiffre de l'année
        $moisEnLettre = chr(date('n') + 64); // convertir le mois en lettres exmepl : 1 = janvier
        $jour = date('d');
    
        $numeroUnique = $annee . $moisEnLettre . $jour . $numeroProductionFormate;
    
        return $numeroUnique;
    }
    
}
