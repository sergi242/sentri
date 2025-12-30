<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\FrontiereCongo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FrontiereCongoController extends Controller
{

    public function index(){
        $frontieres = FrontiereCongo::all();
        return view("admin.frontieres.index",compact("frontieres"));
    }

    public function create(){
        $terminals = ["Port","Aeroport","Terrestre"];
        $departements = Departement::all();
        return view("admin.frontieres.create",compact("terminals","departements"));
    }

    public function store(Request $request){

        $request->validate([
            "lib_frontiere"=>"required|string|unique:frontiere_congos",
            "terminal"=>"required|string",
            "departements_id"=>"required"
        ]);

        $d = ["lib_frontiere"=>$request->frontiere,"terminal"=>$request->terminal,"departements_id"=>$request->departements_id];

        $frontiere = FrontiereCongo::where($d)->first();
        if($frontiere != null){
            toastr()->error("Cette frontière existe déjà");
            return back();
        }

        try {
            $f = new FrontiereCongo;
            $f->lib_frontiere = $request->lib_frontiere;
            $f->terminal = $request->terminal;
            $f->departements_id = $request->departements_id;
            $f->save();
            toastr()->success("Frontière ajoutée avec succès");
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            Log::channel("loggin")->error($e->getMessage());
            return back();
        }
    }

    public function edit($id){

        $frontiere = FrontiereCongo::find($id);
        $terminals = ["Port","Aeroport","Terrestre"];
        $departements = Departement::all();
        if($frontiere == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        return view("admin.frontieres.edit",compact("frontiere","terminals","departements"));
    }

    public function update(Request $request, $id){

        $request->validate([
            "lib_frontiere"=>"required|string",
            "terminal"=>"required|string",
            "departements_id"=>"required"
        ]);

        //$d = ["lib_frontiere"=>$request->frontiere,"terminal"=>$request->terminal,"departements_id"=>$request->departements_id];

        $frontiere = FrontiereCongo::find($id);
        if($frontiere == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        try {
            $frontiere->lib_frontiere = $request->lib_frontiere;
            $frontiere->terminal = $request->terminal;
            $frontiere->departements_id = $request->departements_id;
            $frontiere->save();
            toastr()->success("Frontière modifiée avec succès");
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            Log::channel("loggin")->error($e->getMessage());
            return back();
        }
    }

    public function destroy($id){

        $frontiere = FrontiereCongo::find($id);
        if($frontiere == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        try {
            $frontiere->delete();
            toastr()->success("Frontière supprimée avec succès");
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            Log::channel("loggin")->error($e->getMessage());
            return back();
        }
    }


}
