<?php

namespace Database\Factories;

use App\Models\FrontiereCongo;
use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FluxMigratoire>
 */
class FluxMigratoireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pays = Pays::all()->pluck("id")->toArray();
        $fronts = FrontiereCongo::all()->pluck("id")->toArray();
        $nombre = range(5,100,3);
        $dates = ["2023-11-01","2023-11-02","2023-11-03","2023-11-04","2023-11-05","2023-11-06","2023-11-07","2023-11-08","2023-11-09","2023-11-10","2023-11-11","2023-11-12","2023-11-13","2023-11-14","2023-11-15","2023-11-16","2023-11-17","2023-11-18","2023-11-19","2023-11-20","2023-11-21","2023-11-22","2023-11-23","2023-11-24","2023-11-25","2023-11-26","2023-11-27","2023-11-28","2023-11-29","2023-11-30","2023-12-01","2023-12-02","2023-12-03","2023-12-04","2023-12-05","2023-12-06","2023-12-07","2023-12-08","2023-12-09","2023-12-10","2023-12-11","2023-12-12","2023-12-13","2023-12-14","2023-12-15","2023-12-16","2023-12-17","2023-12-18","2023-12-19","2023-12-20"];
        return [
            "frontieres_id"=>$fronts[rand(0,count($fronts) - 1)],
            "total_entree"=>$nombre[rand(0,count($nombre) -1)],
            "total_sortie"=>$nombre[rand(0,count($nombre) -1)],
            "pays_id"=>$pays[rand(0,count($pays) - 1)],
            "users_id"=>1,
            "date_movement"=>$dates[rand(0,count($dates)-1)]
        ];
    }
}
