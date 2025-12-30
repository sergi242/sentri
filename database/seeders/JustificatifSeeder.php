<?php

namespace Database\Seeders;

use App\Models\Justificatif;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JustificatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pieces = ["Casier judicaire","Certificat médical","Caution de rapartriement","Contrat de travail","Attestation de l'employeur","Quatre (04) photo","Document de commerce","Frais de chancellerie","Timbre fiscal"];
        foreach($pieces as $p){
            $jus = Justificatif::where("piece",$p)->first();
            if($jus == null){
                Justificatif::create(["piece"=>$p]);
            }
        }
    }
}
