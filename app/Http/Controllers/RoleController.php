<?php

namespace App\Http\Controllers;

use App\Models\Fonctionnalite;
use App\Models\Module;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::all();
        return view("admin.role.index",compact("roles"));
    }

    public function store(Request $request){
        $request->validate([
            "lib_role"=>"required|string|unique:roles"
        ]);

        try {
            $role = new Role;
            $role->lib_role = $request->lib_role;
            $role->save();
            toastr()->success("Rôle ajouté avec succès");
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function create(){

    }

    public function edit($id)
{
    $role = Role::find($id);
    
    if($role == null){
        toastr()->error("Impossible de traiter cette requête");
        return back();
    }
    
    // Récupérer tous les modules
    $modules = \App\Models\Module::orderBy('id')->get();
    
    // Récupérer toutes les fonctionnalités groupées par module
    $fonctionnalitesByModule = \App\Models\Fonctionnalite::with('module')
        ->orderBy('modules_id')
        ->orderBy('lib_fonctionnalite')
        ->get()
        ->groupBy('modules_id');
    
    return view("admin.role.edit", compact("role", "modules", "fonctionnalitesByModule"));
}

    public function update(Request $request,$id){
        $role = Role::find($id);

        if($role == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        $request->validate([
            "lib_role"=>"required|string"
        ]);

        try {
            $role->lib_role = $request->lib_role;
            $role->save();

            if($request->fonctionnalites != null){
                $role->fonctionnalites()->sync($request->fonctionnalites);
            }
            toastr()->success("Rôle modifié avec succès");
            return back();
        } catch (Exception $e) {
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id){
        $role = Role::find($id);
        if($role == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $role->delete();
        toastr()->success("Rôle supprimé avec succès");
        return back();
    }
}
