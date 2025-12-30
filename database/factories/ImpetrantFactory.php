<?php

namespace Database\Factories;

use App\Models\Pays;
use App\Models\User;
use App\TechnoDev\src\Facades\TechnoDev;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Impetrant>
 */
class ImpetrantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sexe = ["Masculin","Féminin"];
        $pays = Pays::all()->pluck("id");
        $users = User::all()->pluck("id");
        return [
            "nom"=>fake()->lastName(),
            "prenom"=>fake()->firstName(),
            "sexe"=>$sexe[rand(0,1)],
            "date_naissance"=>fake()->date(),
            "lieu_naissance"=>fake()->city(),
            "nationalites_id"=>$pays[rand(0,$pays->count() -1)],
            "nom_pere"=>fake()->lastName(),
            "prenom_pere"=>fake()->firstName(),
            "nom_mere"=>fake()->lastName(),
            "prenom_mere"=>fake()->firstName(),
            "unique_string"=>"",
            "users_id"=>1
            // "users_id"=>$users[rand(0,$users->count() -1)]
        ];
    }
}
