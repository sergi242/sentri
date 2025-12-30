<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([PaysSeeder::class,GradeSeeder::class,DepartementSeeder::class,ArrondissementSeeder::class,QuartierSeeder::class,JustificatifSeeder::class,CategorieSocioProfessionnelleSeeder::class,RoleSeeder::class,UserSeeder::class,ModuleSeeder::class,FonctionnaliteSeeder::class]);
    }
}
