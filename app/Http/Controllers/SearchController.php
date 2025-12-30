<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Demande;
use App\Models\Impetrant;
use Illuminate\Http\Request;
use App\Models\DocumentDemande;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    public function searchByDocument(){
        $type = request()->get("type");
        $numero_doc = request()->get("numero_doc");
        if(empty($type) && empty($numero_doc)){
            toastr()->error("Veuillez remplir les critères de la recherche");
            return back();
        }
        $demandes = collect([]);
        switch($type){

            case "Visa":
                    $demandes = Demande::where(["numero_document"=>$numero_doc,"type_demande"=>"Visa"])->get();
                break;
            case "crt":
                    $demandes = Demande::where(["numero_document"=>$numero_doc,"type_demande"=>"Carte de résident temporaire"])->get();
                break;
            case "Passeport":
                    $documents = DocumentDemande::where(["type_document"=>"Passeport","numero_document"=>$numero_doc])->get();
                    if($documents->count() > 0){
                        $demandes = $demandes->merge($documents->map->demande);
                    }
                break;
            case "Carte consulaire":
                    $documents = DocumentDemande::where(["type_document"=>"Carte consulaire","numero_document"=>$numero_doc])->get();
                    if($documents->count() > 0){
                        $demandes = $documents->map->demande;
                    }
                break;
            case "Demande":
                $demandes = Demande::where(["uuid"=>$numero_doc])->get();
            break;
        }
        if($demandes->count() > 0){
            $impetrants[0] = $demandes[0]->impetrant;
            //dd($impetrants);
            return view("admin.demandes.searchresults",compact("impetrants"));
        }else{
            toastr()->warning("Aucune donnée trouvée");
            return back();
        }

    }

    public function searchByImpetrant(){
        $nom = request()->get("nom");
        $prenom = request()->get("prenom");
        $debutAge = request()->get("debut_age");
        $finAge = request()->get("fin_age");
        $nationalite = request()->get("nationalites_id");
        // if (empty($nom) && empty($prenom) && empty($debutAge) && empty($finAge) && empty($nationalite)) {
        //     toastr()->error("Veuillez remplir les critères de la recherche");
        //     return back();
        // }
        $query = Impetrant::query()
        ->with(['demandes' => function($query) {
            $query->orderBy('created_at', 'desc');

        }])
        ->where(function ($query) use ($nom, $prenom, $debutAge, $finAge, $nationalite) {
            if (!empty($nom)) {
                $query->where('nom', 'like', '%' . $nom . '%');
            }

            if (!empty($prenom)) {
                $query->where('prenom', 'like', '%' . $prenom . '%');
            }

            if (!empty($debutAge) && !empty($finAge)) {
                $query->whereRaw('YEAR(NOW()) - YEAR(date_naissance) BETWEEN ? AND ?', [$debutAge, $finAge]);
            }

            if (!empty($nationalite)) {
                $query->where('nationalites_id', $nationalite);
            }
        })
        ->orderBy('created_at', 'desc');
        $impetrants = $query->get();

        if ($impetrants->count() > 0) {
            return view("admin.demandes.searchresults", compact("impetrants"));
        } else {
            toastr()->warning("Aucune donnée trouvée");
            return back();
        }
    }

    public function demande(){
        $years = range((date("Y") - 5),date("Y"));
        $month = range(0,11);
        //$daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $months = [
          'Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'
        ];

        return view("admin.charts.charts-demandes",compact("years","month","months"));
    }

    public function flux(){
        $years = range((date("Y") - 5),date("Y"));
        $month = range(0,11);
        //$daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $months = [
          'Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'
        ];

        return view("admin.charts.charts-flux",compact("years","month","months"));
    }

    public function demandes(){
        $month = request("month");
        $year = request("year");
        if($month != "" && $year != ""){
            $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

                $demandes = DB::select("SELECT DAY(date_demande) AS jour, COUNT(*) AS total
                            FROM demandes
                            WHERE
                            MONTH(date_demande) = $month
                            AND YEAR(date_demande) = $year
                            GROUP BY
                                DAY(date_demande)
                            ORDER BY
                                DAY(date_demande)");
                $jours = [];
                $totals = [];
                $donnees = collect($demandes);

                $approuvees = DB::select("SELECT DAY(approval_date) AS jour, COUNT(*) AS total
                FROM demandes
                WHERE statut_demande = 'Approuvée'
                AND
                MONTH(approval_date) = $month
                AND YEAR(approval_date) = $year
                GROUP BY
                    DAY(approval_date)
                ORDER BY
                    DAY(approval_date)");

                $approuvees = collect($approuvees);

                $jap = [];
                $tap = [];

                foreach($donnees as $d){
                    array_push($jours,$d->jour);
                    array_push($totals,$d->total);
                }

                foreach($approuvees as $ap){
                    array_push($jap,$ap->jour);
                    array_push($tap,$ap->total);
                }



                $resultArray = array();
                $resultApprouvees = array();

                $ngivenDates = range(0,$daysInMonth);

                for($i = 0; $i < count($ngivenDates); $i++){
                    $position = array_search($ngivenDates[$i],$jours);
                    $positionAp = array_search($ngivenDates[$i],$jap);
                    if($position !== false){
                        array_push($resultArray,$totals[$position]);
                    }else{
                        array_push($resultArray,0);
                    }

                    if($positionAp !== false){
                        array_push($resultApprouvees,$tap[$positionAp]);
                    }else{
                        array_push($resultApprouvees,0);
                    }
                }

                return response()->json([
                    "jours"=>$ngivenDates,
                    "totals"=>$resultArray,
                    "approuvees"=>$resultApprouvees
                ]);
        }else{
            $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
            $delimiter = ", "; // Delimiter between array elements
            $demandes = DB::select("SELECT MONTH(date_demande) AS mois, COUNT(*) AS total
                        FROM demandes
                        WHERE
                        YEAR(date_demande) = $year
                        GROUP BY
                        MONTH(date_demande)
                        ORDER BY
                        MONTH(date_demande)");
            $jours = [];
            $totals = [];
            $donnees = collect($demandes);

            $approuvees = DB::select("SELECT MONTH(approval_date) AS mois, COUNT(*) AS total
            FROM demandes
            WHERE statut_demande = 'Approuvée'
            AND
            YEAR(approval_date) = $year
            GROUP BY
                MONTH(approval_date)
            ORDER BY
                MONTH(approval_date)");

            $approuvees = collect($approuvees);

            $jap = [];
            $tap = [];

            foreach($donnees as $d){
                array_push($jours,$d->mois);
                array_push($totals,$d->total);
            }

            foreach($approuvees as $ap){
                array_push($jap,$ap->mois);
                array_push($tap,$ap->total);
            }



            $resultArray = array();
            $resultApprouvees = array();

            $ngivenDates = range(0,12);

            for($i = 0; $i < count($ngivenDates); $i++){
                $position = array_search($ngivenDates[$i],$jours);
                $positionAp = array_search($ngivenDates[$i],$jap);
                if($position !== false){
                    array_push($resultArray,$totals[$position]);
                }else{
                    array_push($resultArray,0);
                }

                if($positionAp !== false){
                    array_push($resultApprouvees,$tap[$positionAp]);
                }else{
                    array_push($resultApprouvees,0);
                }
            }

            return response()->json([
                "mois"=>$ngivenDates,
                "totals"=>$resultArray,
                "approuvees"=>$resultApprouvees
            ]);
        }
    }

    public function migratoires(){
        $month = request("month");
        $year = request("year");
        if($month != "" && $year != ""){
            $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
                $delimiter = ", "; // Delimiter between array elements
                $entrees = DB::select("SELECT DAY(date_movement) AS jour, SUM(total_entree) AS total_entree
                            FROM flux_migratoires
                            WHERE

                            MONTH(date_movement) = $month
                            AND YEAR(date_movement) = $year
                            GROUP BY
                                DAY(date_movement)
                            ORDER BY
                                DAY(date_movement)");
                $jours = [];
                $totalsEntree = [];
                $entreesFlux = collect($entrees);

                $sorties = DB::select("SELECT DAY(date_movement) AS jour, SUM(total_sortie) AS total_sortie
                FROM flux_migratoires
                WHERE
                MONTH(date_movement) = $month
                AND YEAR(date_movement) = $year
                GROUP BY
                    DAY(date_movement)
                ORDER BY
                    DAY(date_movement)");

                $sortiesFlux = collect($sorties);

                $joursSorties = [];
                $totalSorties = [];

                foreach($entreesFlux as $d){
                    array_push($jours,$d->jour);
                    array_push($totalsEntree,$d->total_entree);
                }

                foreach($sortiesFlux as $ap){
                    array_push($joursSorties,$ap->jour);
                    array_push($totalSorties,$ap->total_sortie);
                }
                $resultEntrees = array();
                $resultSorties = array();

                $ngivenDates = range(0,$daysInMonth);

                for($i = 0; $i < count($ngivenDates); $i++){
                    $position = array_search($ngivenDates[$i],$jours);
                    $positionSortie = array_search($ngivenDates[$i],$joursSorties);
                    if($position !== false){
                        array_push($resultEntrees,$totalsEntree[$position]);
                    }else{
                        array_push($resultEntrees,0);
                    }

                    if($positionSortie !== false){
                        array_push($resultSorties,$totalSorties[$positionSortie]);
                    }else{
                        array_push($resultSorties,0);
                    }
                }

                return response()->json([
                    "jours"=>$ngivenDates,
                    "entrees"=>$resultEntrees,
                    "sorties"=>$resultSorties
                ]);
        }else{
            $entrees = DB::select("SELECT MONTH(date_movement) AS mois, SUM(total_entree) AS total_entree
                            FROM flux_migratoires
                            WHERE

                            YEAR(date_movement) = $year
                            GROUP BY
                                MONTH(date_movement)
                            ORDER BY
                                MONTH(date_movement)");
                $jours = [];
                $totalsEntree = [];
                $entreesFlux = collect($entrees);

                $sorties = DB::select("SELECT MONTH(date_movement) AS mois, SUM(total_sortie) AS total_sortie
                FROM flux_migratoires
                WHERE
                YEAR(date_movement) = $year
                GROUP BY
                    MONTH(date_movement)
                ORDER BY
                    MONTH(date_movement)");

                $sortiesFlux = collect($sorties);

                $joursSorties = [];
                $totalSorties = [];

                foreach($entreesFlux as $d){
                    array_push($jours,$d->mois);
                    array_push($totalsEntree,$d->total_entree);
                }

                foreach($sortiesFlux as $ap){
                    array_push($joursSorties,$ap->mois);
                    array_push($totalSorties,$ap->total_sortie);
                }
                $resultEntrees = array();
                $resultSorties = array();

                $ngivenDates = range(0,12);

                for($i = 0; $i < count($ngivenDates); $i++){
                    $position = array_search($ngivenDates[$i],$jours);
                    $positionSortie = array_search($ngivenDates[$i],$joursSorties);
                    if($position !== false){
                        array_push($resultEntrees,$totalsEntree[$position]);
                    }else{
                        array_push($resultEntrees,0);
                    }

                    if($positionSortie !== false){
                        array_push($resultSorties,$totalSorties[$positionSortie]);
                    }else{
                        array_push($resultSorties,0);
                    }
                }

                return response()->json([
                    "mois"=>$ngivenDates,
                    "entrees"=>$resultEntrees,
                    "sorties"=>$resultSorties
                ]);
        }
    }
}
