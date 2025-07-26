<?php

namespace App\Http\Controllers;

use App\Models\Impetrant;
use Illuminate\Http\Request;

class ImpetrantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demandes = Impetrant::withCount("demandes")->paginate(20);
        $layout = request()->get("layout");
        if($layout != null){
            request()->session()->put("layout",$layout);
        }
        $status = "La liste des impétrants";
        if(request()->session()->get("layout") =="cards"){
            return view("admin.impetrants.cards_list",compact("demandes","status"));
        }else{
            return view("admin.impetrants.index",compact("demandes","status"));
        }
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

    public function demandes($id)
    {
        // Récupérer l'impétrant avec les demandes triées par date de création
        $impetrant = Impetrant::with(['demandes' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);
    
        if ($impetrant == null) {
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
    
        // Obtenir la demande la plus récente
        $latestDemande = $impetrant->demandes->first();
    
        // Passer la photo de la demande la plus récente à la vue
        return view("admin.demandes.detailsdemandes", [
            'impetrant' => $impetrant,
            'latestDemandePhoto' => $latestDemande ? $latestDemande->photo : null
        ]);
    }
    
    
}
