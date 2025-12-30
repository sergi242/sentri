<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Quartier;
use App\Models\Employeur;
use App\Models\Impetrant;
use App\TechnoDev\src\Facades\TechnoDev;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Demande>
 */
class DemandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $impetrants = Impetrant::all()->pluck("id");
        $types = ["Carte de résident temporaire","Visa"];
        $etatCiviles = ["Célibataire","Marié(e)","Divorcé(e)","Veuf(-ve)"];
        $quartiers = Quartier::all()->pluck("id");
        $numero = range(1,1000,1);
        $professions = ["Développeur Logiciel", "Enseignant", "Médecin", "Ingénieur", "Designer Graphique", "Comptable", "Directeur Marketing", "Chef Cuisinier", "Électricien", "Infirmier/Infirmière", "Photographe", "Écrivain", "Architecte", "Agent de Police", "Mécanicien", "Artiste", "Dentiste", "Représentant Commercial", "Pilote", "Vétérinaire"];
        $employeurs = Employeur::all()->pluck("id");
        $dates = ["2023-11-01","2023-11-02","2023-11-03","2023-11-04","2023-11-05","2023-11-06","2023-11-07","2023-11-08","2023-11-09","2023-11-10","2023-11-11","2023-11-12","2023-11-13","2023-11-14","2023-11-15","2023-11-16","2023-11-17","2023-11-18","2023-11-19","2023-11-20","2023-11-21","2023-11-22","2023-11-23","2023-11-24","2023-11-25","2023-11-26","2023-11-27","2023-11-28","2023-11-29","2023-11-30","2023-12-01","2023-12-02","2023-12-03","2023-12-04","2023-12-05","2023-12-06","2023-12-07","2023-12-08","2023-12-09","2023-12-10","2023-12-11","2023-12-12","2023-12-13","2023-12-14","2023-12-15","2023-12-16","2023-12-17","2023-12-18","2023-12-19","2023-12-20"];
        $tags = ["IMPRESSION","REPRISE"];
        return [
            "impetrants_id" => fake()->unique()->randomElement($impetrants->toArray()),
            "validite" => 1,
            "etat_civil" => $etatCiviles[rand(0,count($etatCiviles)-1)],
            // "date_emission" => ,
            "photo"=>"demandes/wApcVFLPPiaXwmFbhZIa83hDtI54xc8uVtyor4cp.jpg",
            // "date_expiration" => ,
            "quartiers_id" => $quartiers[rand(0,$quartiers->count() -1)],
            "avenue_rue" => fake()->streetName(),
            "numero_adresse" => $numero[rand(0,count($numero) -1)],
            "telephone" => fake()->unique()->phoneNumber(),
            "email" =>fake()->unique()->safeEmail() ,
            "profession" => $professions[rand(0,count($professions)-1)],
            "employeur_id" => $employeurs[rand(0,$employeurs->count() - 1)],
            "type_demande" => $types[rand(0,count($types) - 1)],
            "date_demande" => $dates[rand(0,count($dates)-1)],
            // "statut_demande" => ,
            "tag_demande" => $tags[rand(0,count($tags)-1)],
            "created_by" =>1 ,
            // "approved_by" => ,
            // "export_json" => "",
            // "approval_date" => ,
            "uuid" => TechnoDev::demandeUuid(User::find(1)),
            // "nom_conjoint" => "" ,
            // "attribue" => ,
            // "date_attribution" => ,
            "categorie_socioprof_id" => rand(1,28),
        ];
    }
}
