<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Employeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeurController extends Controller
{

    public function index(){
        $employeurs = Employeur::all();
        return view("admin.employeur.index",compact("employeurs"));
    }
    public function store(Request $request){
        $request->validate([
            "nom_employeur"=>"required|string|unique:employeurs",
            "type"=>"required|string:employeurs",
            "adresse_physique"=>"required|string"
        ]);
        try {
            $employeur = new Employeur;
            $employeur->nom_employeur = $request->nom_employeur ;
            $employeur->type = $request->type;
            $employeur->adresse_physique = $request->adresse_physique;
            $employeur->email = $request->email;
            $employeur->telephone = $request->telephone;
            $employeur->save();
            toastr()->success("Employeur créée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue lors du traitement");
            return back();
        }
    }
    public function edit($id){

        $employeur = Employeur::find($id);
        if($employeur==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
       return view("admin.employeur.edit",compact("employeur"));
    }

    public function update(Request $request,$id){

        $request->validate([
            "nom_employeur"=>"required|string",
            "type"=>"required|string:employeurs",
            "adresse_physique"=>"required|string"
        ]);

        $employeur = Employeur::find($id);
        if($employeur==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
        try {
            $employeur->nom_employeur = $request->nom_employeur ;
            $employeur->type = $request->type;
            $employeur->adresse_physique = $request->adresse_physique;
            $employeur->email = $request->email;
            $employeur->telephone = $request->telephone;
            $employeur->save();
            toastr()->success("Employeur modifiée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue lors du traitement");
            return back();
        }
    }

    public function destroy($id){

        $employeur = Employeur::find($id);
        if($employeur==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
        try {
            $employeur->delete();
            toastr()->success("Employer supprimée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue lors du traitement");
            return back();
        }
    }
}
