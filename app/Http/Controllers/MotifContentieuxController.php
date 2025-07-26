<?php

namespace App\Http\Controllers;

use App\Models\Contentieux;
use App\Models\MotifContentieux;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MotifContentieuxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conts = MotifContentieux::all();
        return view("admin.motifcontentieux.index",compact("conts"));
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
            "lib_motif"=>"required|string|unique:motif_contentieuxes"
        ]);

        try {
            $motif = new MotifContentieux;
            $motif->lib_motif = $request->lib_motif;
            $motif->save();
            toastr()->success("Motif de contentieux ajouté avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur s'est produite");
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
        $motif = MotifContentieux::find($id);

        if($motif == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.motifcontentieux.edit",compact("motif"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "lib_motif"=>"required|string"
        ]);

        $motif = MotifContentieux::find($id);

        if($motif == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        try {
            $motif->lib_motif = $request->lib_motif;
            $motif->save();
            toastr()->success("Motif de contentieux motifié avec succès");
            return redirect()->route("motifs.contentieux.index");
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur s'est produite");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $motif = MotifContentieux::find($id);

        if($motif == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $motif->delete();
        toastr()->success("Motif supprimé avec succès");
        return back();
    }
}
