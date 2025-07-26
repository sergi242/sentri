<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departements = Departement::all();
        return view("admin.departements.index",compact("departements"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "lib_departement"=>"required|string|unique:departements"
        ]);

        try {
            $departement = new Departement;
            $departement->lib_departement = $request->lib_departement;
            $departement->save();
            toastr()->success("Département ajouté avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function arrondissements($id){
        $departement = Departement::find($id);
        return $departement->arrondissements;
    }
}
