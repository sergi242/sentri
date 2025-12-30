<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = ["Gestion des utilisateurs","Gestion des demandes","Gestion du flux migratoir","Reporting","Configuration"];
        foreach ($modules as $module) {
            $mod = Module::where("lib_module",$module)->first();
            if(!$mod){
                Module::create([
                    "lib_module"=> $module
                ]);
            }
        }
    }
}
