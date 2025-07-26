<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{

    public function dashboard(){
        $demandes = collect(DB::select("select count(*) as nombre from demandes where year(date_demande) = year(curdate())"))->first();
        $today = collect(DB::select("select count(*) as nombre from demandes where day(date_demande) = day(curdate()) and month(date_demande)=month(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $week = collect(DB::select("select count(*) as nombre from demandes where week(date_demande) =week(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $month = collect(DB::select("select count(*) as nombre from demandes where month(date_demande) = month(curdate())  and year(date_demande)=year(curdate()) "))->first();
        $year = collect(DB::select("select count(*) as nombre from demandes where year(date_demande)=year(curdate()) "))->first();
        // $month = collect(DB::select("select count(*) as nombre from demandes where month(date_demande) = month(curdate())"))->first();
        $approved = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())",["Approuvée"]))->first();
        $pending = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())",["En attente d'approbation"]))->first();
        $contentieux = collect(DB::select("select count(*) as nombre from demandes where statut_demande = ? and year(date_demande) = year(curdate())",["Envoyée au contentieux"]))->first();
        $annee = collect(DB::select("select count(*) as nombre from demandes where year(date_demande) = year(curdate())"))->first();
        $impetrants = collect(DB::select("select count(*) as nombre from impetrants where year(created_at) = year(curdate())"))->first();
        $renouvellements = Demande::groupBy('impetrants_id')->havingRaw('COUNT(impetrants_id) > 1')->get();

        // attribution
        $todayAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and day(date_demande) = day(curdate()) and month(date_demande)=month(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $weekAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and week(date_demande) =week(curdate()) and year(date_demande)=year(curdate()) "))->first();
        $monthAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and month(date_demande) = month(curdate())  and year(date_demande)=year(curdate()) "))->first();
        $yearAtt = collect(DB::select("select count(*) as nombre from demandes where attribue=1 and year(date_demande)=year(curdate()) "))->first();
        $flux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where year(date_movement) = year(curdate())"))->first();

         // attribution
         $todayFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where day(date_movement)=day(curdate()) and month(date_movement)=month(curdate()) and year(date_movement) = year(curdate())"))->first();
         $weekFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where week(date_movement)=week(curdate()) and year(date_movement) = year(curdate())"))->first();
         $monthFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where month(date_movement)=month(curdate()) and year(date_movement) = year(curdate())"))->first();
         $yearFlux = collect(DB::select("select sum(total_entree) as total_entree, sum(total_sortie) as total_sortie from flux_migratoires where year(date_movement) = year(curdate())"))->first();
        // dd($yearFlux);
         return view("admin.home.dashboard",compact("annee","demandes","impetrants","renouvellements","today","month","approved","pending","contentieux","flux","year","week","todayAtt","weekAtt","monthAtt","yearAtt","todayFlux","weekFlux","monthFlux","yearFlux"));

    }


}
