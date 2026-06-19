<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(User::whereEmail("sergi.ondele@gmail.com")->first() == null){
            User::create([
                "prenom"=>"Sergi",
                "nom"=>"Ondele",
                "email"=>"sergi.ondele@gmail.com",
                "roles_id"=>1,
                "password"=>Hash::make("123456"),
                "grades_id"=>6
            ]);
        }

        if(User::whereEmail("brazza01@dmce.local")->first() == null){
            User::create([
                "prenom"=>"Admin",
                "nom"=>"Brazza",
                "email"=>"brazza01@dmce.local",
                "roles_id"=>1,
                "password"=>Hash::make("123"),
                "grades_id"=>6
            ]);
        }
    }
}
