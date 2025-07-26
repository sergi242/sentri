<?php

namespace App\TechnoDev\src\Classes;

use Carbon\Carbon;
use App\Models\Demande;
use App\Models\Impetrant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;

class TechnoDev{

    public function impetrantUniqueString(Impetrant $impetrant){
        $str = $impetrant->nom;
        $str .= $impetrant->prenom;
        $str .= $impetrant->sexe;
        $str .= $impetrant->date_naissance;
        // $str .= $impetrant->lieu_naissance;
        $str .= $impetrant->nationalites_id;
        // $str .= $impetrant->nom_pere;
        // $str .= $impetrant->prenom_pere;
        // $str .= $impetrant->nom_mere;
        // $str .= $impetrant->prenom_mere;
        return Str::upper($str);
    }

    public function tauxSimilarity($s1,$s2){
        $distance = levenshtein($s1, $s2);
        $similarity1 = 100 - ($distance / max(strlen($s1), strlen($s2))) * 100;
        return $similarity1;
    }

    public function demandeUuid(User $user){
        // 005_18062023_0000000001
        $userseq = self::strpad($user->id,3);
        $demande = Demande::orderBy("created_at","desc")->where("created_by",$user->id)->first();
        $demandeNumber = 1;
        if($demande == null){
            $demandeNumber = 1;
            $str_uuid = $userseq."_".date("dmY")."_".self::strpad($demandeNumber,10);
            return $str_uuid;
        }else{
            $uuid = $demande->uuid;
            $demandeNumber = (int) substr($uuid,13);
            $str_uuid = $userseq."_".date("dmY")."_".self::strpad($demandeNumber,10);
            return $str_uuid;
        }
    }

    public function strpad($value,$zeros){
        return is_int($value) && is_int($zeros) ? str_pad($value,$zeros,"0",STR_PAD_LEFT):0;
    }

    public function joinFluxData(int $pays_id, int $frontieres_id, string $from, string $to){
        $data = DB::select("select pays_id,frontieres_id, sum(total_entree) as tentree, sum(total_sortie) as tsortie from flux_migratoires where pays_id=? and frontieres_id=? and date(date_movement) between ? and ? ",
            [$pays_id,$frontieres_id,$from,$to]);

            return collect($data)->first();
    }

    public function timespan($inputtime)
    {
        // Convert the input date and time to a Carbon instance
        $dateTime = Carbon::parse($inputtime);

        // Get the current date and time
        $now = Carbon::now();

        // Calculate the difference in seconds
        $diffInSeconds = $now->diffInSeconds($dateTime);

        // Calculate the difference in minutes
        $diffInMinutes = $now->diffInMinutes($dateTime);

        // Calculate the difference in hours
        $diffInHours = $now->diffInHours($dateTime);

        // Calculate the difference in days
        $diffInDays = $now->diffInDays($dateTime);

        // Calculate the difference in weeks
        $diffInWeeks = $now->diffInWeeks($dateTime);

        // Calculate the difference in months
        $diffInMonths = $now->diffInMonths($dateTime);

        // Calculate the difference in years
        $diffInYears = $now->diffInYears($dateTime);

        // Return an associative array with the calculated values
        $sorties =  [
            "seconds" => $diffInSeconds,
            "minutes" => $diffInMinutes,
            "hours" => $diffInHours,
            "days" => $diffInDays,
            "weeks" => $diffInWeeks,
            "months" => $diffInMonths,
            "years" => $diffInYears
        ];

        // return $sorties;

        if($diffInSeconds > 0 && $diffInSeconds < 60 ){
            return "$diffInSeconds seconde(s)";
        }

        if($diffInSeconds > 60 && $diffInSeconds < 3600 ){
            return "$diffInMinutes minute(s)";
        }

        if($diffInSeconds > 3600 && $diffInSeconds < 86400 ){
            return "$diffInHours heure(s)";
        }

        if($diffInSeconds > 86400 && $diffInSeconds < 604800 ){
            return "$diffInDays jour(s)";
        }

        if($diffInSeconds > 604800 && $diffInSeconds < 2419200 ){
            return "$diffInWeeks semaine(s)";
        }

        if($diffInMonths > 0 && $diffInMonths < 12){
            return "$diffInMonths mois";
        }

        if($diffInYears > 0){
            return "$diffInYears année(s)";
        }
    }
}
