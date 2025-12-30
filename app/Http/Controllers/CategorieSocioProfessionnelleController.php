<?php

namespace App\Http\Controllers;

use App\Models\CategorieSocioProfessionnelle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategorieSocioProfessionnelleController extends Controller
{
    public function index(){
        $categories = CategorieSocioProfessionnelle::all();
        return view("admin.categorieprofessionnelle.index",compact("categories"));
    }

    public function create(){

    }

    public function edit($id){

        $categorie = CategorieSocioProfessionnelle::find($id);
        if($categorie==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
       return view("admin.categorieprofessionnelle.edit",compact("categorie"));
    }

    public function store(Request $request){
        $request->validate([
            "categorie"=>"required|string|unique:categorie_socio_professionnelles"
        ]);

        try {
            $categorie = new CategorieSocioProfessionnelle;
            $categorie->categorie = $request->categorie;
            $categorie->save();
            toastr()->success("Catégorie créée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue lors du traitement");
            return back();
        }
    }

    public function update(Request $request,$id){
        $request->validate([
            "categorie"=>"required|string"
        ]);
        $categorie = CategorieSocioProfessionnelle::find($id);
        if($categorie==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
        try {
            $categorie->categorie = $request->categorie;
            $categorie->save();
            toastr()->success("Catégorie modifiée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue lors du traitement");
            return back();
        }
    }

    public function destroy($id){

        $categorie = CategorieSocioProfessionnelle::find($id);
        if($categorie==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
        try {
            $categorie->delete();
            toastr()->success("Catégorie supprimée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue lors du traitement");
            return back();
        }
    }


}
