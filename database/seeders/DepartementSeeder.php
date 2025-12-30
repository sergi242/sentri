<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $tr_departement = array(
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000001",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "POPULATION_DEPARTEMENT" => 2051565,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000002",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "POPULATION_DEPARTEMENT" => 1264637,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000003",
                "LIB_DEPARTEMENT" => "NIARI",
                "POPULATION_DEPARTEMENT" => 349856,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000004",
                "LIB_DEPARTEMENT" => "LEKOUMOU",
                "POPULATION_DEPARTEMENT" => 145850,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000005",
                "LIB_DEPARTEMENT" => "PLATEAUX",
                "POPULATION_DEPARTEMENT" => 264124,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000006",
                "LIB_DEPARTEMENT" => "POOL",
                "POPULATION_DEPARTEMENT" => 386508,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000007",
                "LIB_DEPARTEMENT" => "LIKOUALA",
                "POPULATION_DEPARTEMENT" => 233172,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000008",
                "LIB_DEPARTEMENT" => "CUVETTE",
                "POPULATION_DEPARTEMENT" => 236170,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000009",
                "LIB_DEPARTEMENT" => "CUVETTE-OUEST",
                "POPULATION_DEPARTEMENT" => 110436,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000010",
                "LIB_DEPARTEMENT" => "SANGHA",
                "POPULATION_DEPARTEMENT" => 129832,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000011",
                "LIB_DEPARTEMENT" => "KOUILOU",
                "POPULATION_DEPARTEMENT" => 114019,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA0000000012",
                "LIB_DEPARTEMENT" => "BOUENZA",
                "POPULATION_DEPARTEMENT" => 467570,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
            array(
                "CODE_DEPARTEMENT" => "DEPA9999999999",
                "LIB_DEPARTEMENT" => "AUTRES",
                "POPULATION_DEPARTEMENT" => 0,
                "SUPERFICIE_DEPARTEMENT" => NULL,
                "SUPPRIMER" => 0,
                "GEO_LOCALISATION" => NULL,
            ),
        );

        foreach($data as $d){
            $pays = Departement::where("lib_departement",$d["LIB_DEPARTEMENT"])->first();
            if($pays == null){
                Departement::create([
                    "lib_departement"=>$d["LIB_DEPARTEMENT"]
                ]);
            }
        }

    }
}
