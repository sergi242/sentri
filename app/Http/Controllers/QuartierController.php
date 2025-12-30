<?php

namespace App\Http\Controllers;

use App\Models\Arrondissement;
use App\Models\Quartier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuartierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quartiers = Quartier::all();
        $arrondissements = Arrondissement::all();
        return view("admin.quartiers.index",compact("quartiers","arrondissements"));
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
        $request->validate([
            "lib_quartier"=>"required|string",
            "arrondissements_id"=>"required"
        ]);

        try {
            $quartier = new Quartier;
            $quartier->lib_quartier = $request->lib_quartier;
            $quartier->arrondissements_id = $request->arrondissements_id;
            $quartier->save();
            toastr()->success("Quartier ajouté avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back();
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

    }


}
