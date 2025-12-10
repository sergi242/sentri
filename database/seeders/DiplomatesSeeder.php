<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiplomatesSeeder extends Seeder
{
    public function run(): void
    {
        $structures = [
            'Ambassade',
            'DMG',
            'WCS',
            'APPO',
            'Catholique',
            'HCR',
            'CRS',
            'Présidence de la République',
            'PAM',
            'Ciespac',
            'Unesco',
            'BRCC',
            'Islam',
            'UNFPA',
            'UNICEF',
            'Consulat Honoraire',
            'Asudh',
            'AFD',
            'ESSOR',
            'ASECNA',
            'PNUD',
            'CIPD/IE',
            'WFP',
            'UNHCR',
            'FME',
            'Aspinall Fondation',
            'ASI',
        ];

        foreach ($structures as $s) {
            DB::table('employeurs')->insert([
                'nom_employeur'    => $s,
                'type'             => 'Diplomate',
                'adresse_physique' => 'Brazzaville',
            ]);
        }
    }
}

