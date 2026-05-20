<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\FluxMigratoire;
use App\Models\FrontiereCongo;
use App\Models\Pays;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FluxMigratoireController extends Controller
{
   public function index(Request $request)
{
    $query = FluxMigratoire::with(['frontiere', 'pays']);

    if ($request->filled('frontiere_id')) {
        $query->where('frontieres_id', $request->frontiere_id);
    }

    if ($request->filled('pays_id')) {
        $query->where('pays_id', $request->pays_id);
    }

    if ($request->filled('type_flux')) {
        if ($request->type_flux === 'entree') {
            $query->where('total_entree', '>', 0)->where('total_sortie', 0);
        } else {
            $query->where('total_sortie', '>', 0)->where('total_entree', 0);
        }
    }

    if ($request->filled('date_debut')) {
        $query->where('date_movement', '>=', $request->date_debut);
    }

    if ($request->filled('date_fin')) {
        $query->where('date_movement', '<=', $request->date_fin);
    }

    $flux       = $query->orderBy('date_movement', 'desc')->get();
    $frontieres = \App\Models\FrontiereCongo::orderBy('lib_frontiere')->get();
    $pays       = \App\Models\Pays::orderBy('lib_pays')->get();

    return view('admin.flux.index', compact('flux', 'frontieres', 'pays'));
}
    public function create(){
        $frontieres = FrontiereCongo::all();
        $departements = Departement::all();
        $pays = Pays::all();
        return view("admin.flux.create",compact("frontieres","departements","pays"));
    }

    public function getFrontieresByDepartement($id){
        $frontieres = FrontiereCongo::where("departements_id",$id)->get();
        return response()->json($frontieres);
    }

    public function store(Request $request){
        $request->validate([
            "frontieres_id"=>"required",
            "total_entree"=>"required|numeric",
            "total_sortie"=>"required|numeric",
            "pays_id"=>"required",
            "date_movement"=>"required"
        ]);

        try {
            $flux = new FluxMigratoire;
            $flux->frontieres_id = $request->frontieres_id;
            $flux->total_entree = $request->total_entree;
            $flux->total_sortie = $request->total_sortie;
            $flux->pays_id = $request->pays_id;
            $flux->date_movement = $request->date_movement;
            $flux->users_id = Auth::user()->id;
            $flux->save();
            toastr()->success("Données enregistrée avec succès");
            return back();
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function edit($id){
        $flux = FluxMigratoire::find($id);
        $frontieres = FrontiereCongo::all();
        $departements = Departement::all();
        $pays = Pays::all();
        if($flux == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }
        return view("admin.flux.edit",compact("flux","frontieres","departements","pays"));
    }

    public function update(Request $request, $id){
        $flux = FluxMigratoire::find($id);
        if($flux == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $request->validate([
            "frontieres_id"=>"required",
            "total_entree"=>"required|numeric",
            "total_sortie"=>"required|numeric",
            "pays_id"=>"required",
            "date_movement"=>"required"
        ]);

        try {
            $flux->frontieres_id = $request->frontieres_id;
            $flux->total_entree = $request->total_entree;
            $flux->total_sortie = $request->total_sortie;
            $flux->pays_id = $request->pays_id;
            $flux->date_movement = $request->date_movement;
            $flux->save();
            toastr()->success("Données modifiée avec succès");
            return redirect()->route("flux.index");
        } catch (Exception $e) {
            Log::channel("loggin")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function destroy($id){
        $flux = FluxMigratoire::find($id);
        if($flux == null){
            toastr()->error("Impossible de traiter cette requête");
            return back();
        }

        $flux->delete();
        toastr()->success("Donnée supprimée avec succès");
        return back();
    }

    public function fluxmigratoiresatentre(){
        //$demandes = Demande::whereWeek("date_demande", Carbon::now()->week)->get();
        $critere = request("critere");
        $demandes = collect([]);
        switch ($critere){
            case "jour":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id where date(date_movement) =date(curdate()) and flux.total_entree > 0"));
            break;
            case "semaine":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id where week(date_movement) =week(curdate()) and year(date_movement)=year(curdate()) and flux.total_entree > 0"));
                break;
            case "mois":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id  where month(date_movement) =month(curdate()) and year(date_movement)=year(curdate()) and flux.total_entree > 0"));
                break;
            case "annee":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id  where year(date_movement) =year(curdate()) and flux.total_entree > 0"));
                break;
                default:
                $flux = collect([]);
        }
        // dd($critere);
        return view("admin.flux.statsmigrationentre",compact("flux","critere") );
    }
    public function fluxmigratoiresatsortie(){
        //$demandes = Demande::whereWeek("date_demande", Carbon::now()->week)->get();
        $critere = request("critere");
        $demandes = collect([]);
        switch ($critere){
            case "jour":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id where date(date_movement) =date(curdate()) and flux.total_sortie > 0"));
            break;
            case "semaine":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id where week(date_movement) =week(curdate()) and year(date_movement)=year(curdate()) and flux.total_sortie > 0"));
                break;
            case "mois":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id  where month(date_movement) =month(curdate()) and year(date_movement)=year(curdate()) and flux.total_sortie > 0"));
                break;
            case "annee":
                $flux = collect(DB::select("select *  from flux_migratoires flux join frontiere_congos frontiere on flux.frontieres_id = frontiere.id join pays p on flux.pays_id = p.id  where year(date_movement) =year(curdate()) and flux.total_sortie > 0"));
                break;
                default:
                $flux = collect([]);
        }
        // dd($critere);
        return view("admin.flux.statsmigrationsortie",compact("flux","critere") );
    }
}
