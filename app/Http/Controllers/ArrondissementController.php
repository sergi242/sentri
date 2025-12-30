<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrondissement;
use App\Models\Departement;

class ArrondissementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $arrondissements = Arrondissement::orderBy("departements_id","asc")->get();
        return view("admin.arrondissements.index",compact("arrondissements"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function quartiers($id){
        $arrondissement = Arrondissement::find($id);
        return $arrondissement->quartiers;
    }
}
