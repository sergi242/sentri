<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GrapheController extends Controller
{

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
