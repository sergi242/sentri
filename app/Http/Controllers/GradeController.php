<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GradeController extends Controller
{
    private ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->api->getGradesManage();
        $grades   = collect($response['data'] ?? (isset($response['error']) ? [] : $response));

        return view("admin.grades.index", compact("grades"));
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
            "grade" => "required|string",
        ]);

        try {
            $result = $this->api->createGrade(['grade' => $request->grade]);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back()->withInput();
            }

            toastr()->success("Grade créé avec succès");
            return back();
        } catch (Exception $e) {
            Log::error($e->getMessage());
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
        $result = $this->api->getGradeManage($id);

        if (empty($result) || !empty($result['error'])) {
            toastr()->warning("Impossible de traiter cette requête");
            return back();
        }

        $grade = (object) ($result['data'] ?? $result);

        return view("admin.grades.edit", compact("grade"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "grade" => "required|string",
        ]);

        try {
            $result = $this->api->updateGrade($id, ['grade' => $request->grade]);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back()->withInput();
            }

            toastr()->success("Grade modifié avec succès");
            return back();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->api->deleteGrade($id);

            if (!empty($result['error'])) {
                toastr()->error($result['message'] ?? "Une erreur est survenue");
                return back();
            }

            toastr()->success("Grade supprimé avec succès");
            return back();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            toastr()->error("Une erreur est survenue");
            return back();
        }
    }
}
