<?php

namespace Database\Seeders;

use App\Models\CategorieSocioProfessionnelle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSocioProfessionnelleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            "Professionnels de santé",
            "Agents des affaires sociales",
            "Professionnels de la communication (agents de reportage)",
            "Force publique et familles (aux points d’entrée et aux frontières)",
            "Personnel des Nations Unies",
            "Diplomates",
            "Personnes âgées (≥ 60 ans)",
            "Personnes porteuses d’une comorbidité",
            "Personnel des agences de voyage",
             "Personnel des banques",
             "Enseignants",
             "Etudiants",
             "Commerçants nationaux",
             "Commerçants expatriés",
             "Prisonniers et agents en milieu carcéral",
             "Personnels des firmes multinationales",
             "Les populations spéciales (réfugiés et déplacés internes)",
             "Professionnels de sexe",
             "Personnels bars-dancing, Hôtel, restaurants, salons de coiffure",
            "Sportif",
            "Autres"
        ];
        foreach($categories as $cat){
            $in = CategorieSocioProfessionnelle::where("categorie",$cat)->first();
            if($in == null){
                CategorieSocioProfessionnelle::create([
                    "categorie"=>$cat
                ]);
            }
        }

    }
}
