<?php

namespace Database\Seeders;

use App\Models\Fonctionnalite;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ["SuperAdmin","Opérateur de saisi","Approbateur des demandes","Service contentieux","Attribution Visa et CRT"];
        foreach($roles as $role){
            $ro = Role::where("lib_role",$role)->first();
            if($ro==null){
                $r = Role::create([
                    "lib_role"=>$role
                ]);
            }
        }
    }
}
