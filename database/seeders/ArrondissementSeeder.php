<?php

namespace Database\Seeders;

use App\Models\Arrondissement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArrondissementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $tr_arrondissement = array(
            array(
                "LIB_ARRONDISSEMENT" => "FOUNDOU-FOUNDOU",
                "LIB_DEPARTEMENT" => "NIARI",
                "ID_DEPARTEMENT" => 3,
                "LIB_COMMUNE" => "DOLISIE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "YOULOU-POUNGUI",
                "LIB_DEPARTEMENT" => "NIARI",
                "ID_DEPARTEMENT" => 3,
                "LIB_COMMUNE" => "DOLISIE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "BOUALI",
                "LIB_DEPARTEMENT" => "NIARI",
                "ID_DEPARTEMENT" => 3,
                "LIB_COMMUNE" => "MOSSENDJO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "ITSIBOU",
                "LIB_DEPARTEMENT" => "NIARI",
                "ID_DEPARTEMENT" => 3,
                "LIB_COMMUNE" => "MOSSENDJO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "SIBITI",
                "LIB_DEPARTEMENT" => "LEKOUMOU",
                "ID_DEPARTEMENT" => 4,
                "LIB_COMMUNE" => "SIBITI",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "LUMUMBA",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "ID_DEPARTEMENT" => 2,
                "LIB_COMMUNE" => "POINTE-NOIRE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MVOUMVOU",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "ID_DEPARTEMENT" => 2,
                "LIB_COMMUNE" => "POINTE-NOIRE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "TIE-TIE",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "ID_DEPARTEMENT" => 2,
                "LIB_COMMUNE" => "POINTE-NOIRE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "LOANDJILI",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "ID_DEPARTEMENT" => 2,
                "LIB_COMMUNE" => "POINTE-NOIRE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MONGO-POUKOU",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "ID_DEPARTEMENT" => 2,
                "LIB_COMMUNE" => "POINTE-NOIRE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "NGOYO",
                "LIB_DEPARTEMENT" => "POINTE-NOIRE",
                "ID_DEPARTEMENT" => 2,
                "LIB_COMMUNE" => "POINTE-NOIRE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "DJAMBALA",
                "LIB_DEPARTEMENT" => "PLATEAUX",
                "ID_DEPARTEMENT" => 5,
                "LIB_COMMUNE" => "DJAMBALA",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "KINKALA",
                "LIB_DEPARTEMENT" => "POOL",
                "ID_DEPARTEMENT" => 6,
                "LIB_COMMUNE" => "KINKALA",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "KINTELE",
                "LIB_DEPARTEMENT" => "POOL",
                "ID_DEPARTEMENT" => 6,
                "LIB_COMMUNE" => "KINTELE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "IMPFONDO",
                "LIB_DEPARTEMENT" => "LIKOUALA",
                "ID_DEPARTEMENT" => 7,
                "LIB_COMMUNE" => "IMPFONDO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "OWANDO",
                "LIB_DEPARTEMENT" => "CUVETTE",
                "ID_DEPARTEMENT" => 8,
                "LIB_COMMUNE" => "OWANDO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "OYO",
                "LIB_DEPARTEMENT" => "CUVETTE",
                "ID_DEPARTEMENT" => 8,
                "LIB_COMMUNE" => "OYO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "EWO",
                "LIB_DEPARTEMENT" => "CUVETTE-OUEST",
                "ID_DEPARTEMENT" => 9,
                "LIB_COMMUNE" => "EWO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "OUESSO",
                "LIB_DEPARTEMENT" => "SANGHA",
                "ID_DEPARTEMENT" => 10,
                "LIB_COMMUNE" => "OUESSO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "LOANGO",
                "LIB_DEPARTEMENT" => "KOUILOU",
                "ID_DEPARTEMENT" => 11,
                "LIB_COMMUNE" => "LOANGO",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MAKELEKELE",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "BACONGO",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "POTO-POTO",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MOUNGALI",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "OUENZE",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "TALANGAI",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MFILOU",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MADIBOU",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "DJIRI",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MADINGOU",
                "LIB_DEPARTEMENT" => "BOUENZA",
                "ID_DEPARTEMENT" => 12,
                "LIB_COMMUNE" => "MADINGOU",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "MWANA-NTO",
                "LIB_DEPARTEMENT" => "BOUENZA",
                "ID_DEPARTEMENT" => 12,
                "LIB_COMMUNE" => "NKAYI",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "SOULOUKA",
                "LIB_DEPARTEMENT" => "BOUENZA",
                "ID_DEPARTEMENT" => 12,
                "LIB_COMMUNE" => "NKAYI",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "DOLISIE",
                "LIB_DEPARTEMENT" => "NIARI",
                "ID_DEPARTEMENT" => 3,
                "LIB_COMMUNE" => "DOLISIE",
            ),
            array(
                "LIB_ARRONDISSEMENT" => "LOCALITE INDEFINIE",
                "LIB_DEPARTEMENT" => "BRAZZAVILLE",
                "ID_DEPARTEMENT" => 1,
                "LIB_COMMUNE" => "BRAZZAVILLE",
            ),
        );

        foreach($data as $d){
            $pays = Arrondissement::where("lib_arrondissement",$d["LIB_ARRONDISSEMENT"])->first();
            if($pays == null){
                Arrondissement::create([
                    "lib_arrondissement"=>$d["LIB_ARRONDISSEMENT"],
                    "departements_id"=>$d["ID_DEPARTEMENT"]
                ]);
            }
        }
    }
}
