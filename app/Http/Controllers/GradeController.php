<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::all();
        return view("admin.grades.index",compact("grades"));
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
            "grade"=>"required|string|unique:grades"
        ]);

        try {
            $grade = new Grade;
            $grade->grade = $request->grade;
            $grade->save();
            toastr()->success("Grade créé avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue");
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
        $grade = Grade::find($id);
        if($grade==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.grades.edit",compact("grade"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            "grade"=>"required|string"
        ]);

        $grade = Grade::find($id);
        if($grade==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }

        try {
            $grade->grade = $request->grade;
            $grade->save();
            toastr()->success("Grade modifié avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::find($id);
        if($grade==null){
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }

        try {
            $grade->delete();
            toastr()->success("Grade supprimé avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }
    }
}
