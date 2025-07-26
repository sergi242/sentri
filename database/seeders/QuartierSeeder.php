<?php

namespace Database\Seeders;

use App\Models\Quartier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuartierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quartiers = array(
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 51", "lib_quartier"=>"Mandzandza"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 52", "lib_quartier"=>"Massamba Raphaël"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 53", "lib_quartier"=>"Mandzandza-Zando"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 54", "lib_quartier"=>"Peyre Pierre"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 55", "lib_quartier"=>"Mpiere-Mpiere"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 56", "lib_quartier"=>"Bouemba"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 57", "lib_quartier"=>"Mouleke"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 58", "lib_quartier"=>"Moukondo"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 58 bis", "lib_quartier"=>"Kimbangou-Mikalou"],
            ["arrondissements_id"=>"25", "code_quartier"=>"Le quartier 59", "lib_quartier"=>"Mpila Cent Fils"],

            //makelekele
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 01", "lib_quartier" => "Centre Sportif"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 02", "lib_quartier" => "Mayoma"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 03", "lib_quartier" => "Météo"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 04", "lib_quartier" => "Moukoudzi Ngouaka"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 05", "lib_quartier" => "Ngangouoni"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 06", "lib_quartier" => "Diata"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 07", "lib_quartier" => "Kingouari"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 08", "lib_quartier" => "Kinsoundi"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 09", "lib_quartier" => "Niania Sita dia tsiolo"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 10", "lib_quartier" => "Mamba"],
            ["arrondissements_id" => "21", "code_quartier" => "Le quartier 11", "lib_quartier" => "Ngoma"],

            //
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 21", "lib_quartier" => "La glacière"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 22", "lib_quartier" => "Dahomey"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 23", "lib_quartier" => "Mbama"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 24", "lib_quartier" => "Nimbi"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 25", "lib_quartier" => "Nkéoua Joseph"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 26", "lib_quartier" => "Cinq chemins"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 27", "lib_quartier" => "Tahiti"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 28", "lib_quartier" => "Saint Pierre Claver"],
            ["arrondissements_id" => 22, "code_quartier" => "Le quartier 29", "lib_quartier" => "Mpissa"],
            //
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 41", "lib_quartier" => "Ecole de Peinture de Poto-Poto"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 42", "lib_quartier" => "Anciens Combattants"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 43", "lib_quartier" => "Plateau des 15 ans"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 44", "lib_quartier" => "Dix Maisons"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 45", "lib_quartier" => "CEG de la Paix"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 46", "lib_quartier" => "Marché 10 F"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 47", "lib_quartier" => "CEG Matsoua"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 48", "lib_quartier" => "Moukondo"],
            ["arrondissements_id" => 24, "code_quartier" => "Le quartier 49", "lib_quartier" => "La Poudrière"],

            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 61", "lib_quartier" => "Mpila"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 62", "lib_quartier" => "Intendance"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 63", "lib_quartier" => "Texaco Tsiémé"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 64", "lib_quartier" => "Fleuve Congo"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 65", "lib_quartier" => "Joseph GOBALI"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 66", "lib_quartier" => "Champ de Tir"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 67", "lib_quartier" => "Gaston LENDA"],
            ["arrondissements_id" => 26, "code_quartier" => "Le quartier 68", "lib_quartier" => "Maman MBOUALE"],

            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 901", "lib_quartier" => "Mikalou Madzouna"],
            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 902", "lib_quartier" => "Jacques Opangault"],
            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 903", "lib_quartier" => "Matari"],
            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 904", "lib_quartier" => "Nkombo"],
            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 905", "lib_quartier" => "Itatolo"],
            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 906", "lib_quartier" => "Impoh Manianga"],
            ["arrondissements_id" => 29, "code_quartier" => "Le quartier 907", "lib_quartier" => "Makabandilou"],
        );
        foreach($quartiers as $q){
            $quart = Quartier::where("code_quartier",$q["code_quartier"])->where("arrondissements_id",$q["arrondissements_id"])->first();
            if($quart == null){
                Quartier::create($q);
            }
        }
    }
}
