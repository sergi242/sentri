<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Fonctionnalite;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FonctionnaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fonctionnalites = [
            //Gestion des utilisateurs
            ["lib_fonctionnalite"=>"Gestion des utilisateurs","unique_key_string"=>"users.menu","fonctionnalite_parent"=>null,"modules_id"=>1], //1
            ["lib_fonctionnalite"=>"Voir les utilisateurs","unique_key_string"=>"users.view","fonctionnalite_parent"=>1,"modules_id"=>1], //2
            ["lib_fonctionnalite"=>"Créer des utilisateurs","unique_key_string"=>"users.create","fonctionnalite_parent"=>1,"modules_id"=>1], //3
            ["lib_fonctionnalite"=>"Modifier les utilisateurs","unique_key_string"=>"users.edit","fonctionnalite_parent"=>1,"modules_id"=>1], //4
            ["lib_fonctionnalite"=>"Supprimer les utilisateurs","unique_key_string"=>"users.destroy","fonctionnalite_parent"=>1,"modules_id"=>1], //5
            ["lib_fonctionnalite"=>"Voir le profil des utilisateurs","unique_key_string"=>"users.profiles","fonctionnalite_parent"=>1,"modules_id"=>1], //6
            ["lib_fonctionnalite"=>"Activer un utilisateur","unique_key_string"=>"users.activate","fonctionnalite_parent"=>1,"modules_id"=>1], //7
            ["lib_fonctionnalite"=>"Désactiver un utilisateur","unique_key_string"=>"users.deactivate","fonctionnalite_parent"=>1,"modules_id"=>1], //8
            // Gestion des rôles
            ["lib_fonctionnalite"=>"Gestion des rôles","unique_key_string"=>"roles.menu","fonctionnalite_parent"=>null,"modules_id"=>1],//9
            ["lib_fonctionnalite"=>"Voir les rôles","unique_key_string"=>"roles.view","fonctionnalite_parent"=>9,"modules_id"=>1], //10
            ["lib_fonctionnalite"=>"Création des rôles","unique_key_string"=>"roles.create","fonctionnalite_parent"=>9,"modules_id"=>1], //11
            ["lib_fonctionnalite"=>"Modifier les rôles","unique_key_string"=>"roles.edit","fonctionnalite_parent"=>9,"modules_id"=>1], //12
            ["lib_fonctionnalite"=>"Surpprimer les rôles","unique_key_string"=>"roles.destroy","fonctionnalite_parent"=>9,"modules_id"=>1], //13

            // Gestion des demandes
            ["lib_fonctionnalite"=>"Gestion des demandes","unique_key_string"=>"demandes.menu","fonctionnalite_parent"=>null,"modules_id"=>2], //14
            ["lib_fonctionnalite"=>"Voir toutes les demandes","unique_key_string"=>"demandes.view.all","fonctionnalite_parent"=>14,"modules_id"=>2], //15
            ["lib_fonctionnalite"=>"Voir les demandes en attente","unique_key_string"=>"demandes.view.pending","fonctionnalite_parent"=>14,"modules_id"=>2], //16
            ["lib_fonctionnalite"=>"Voir les demandes approuvées","unique_key_string"=>"demandes.view.approved","fonctionnalite_parent"=>14,"modules_id"=>2], //17
            ["lib_fonctionnalite"=>"Voir les demandes au contenteux","unique_key_string"=>"demandes.view.contentieux","fonctionnalite_parent"=>14,"modules_id"=>2], //18
            ["lib_fonctionnalite"=>"Voir les demandes attribuées","unique_key_string"=>"demandes.view.attribue","fonctionnalite_parent"=>14,"modules_id"=>2], //19
            ["lib_fonctionnalite"=>"Voir les similarités des demandes","unique_key_string"=>"demandes.view.similar","fonctionnalite_parent"=>14,"modules_id"=>2], //20
            ["lib_fonctionnalite"=>"Renouvellement des demandes","unique_key_string"=>"demandes.renew","fonctionnalite_parent"=>14,"modules_id"=>2], //21
            ["lib_fonctionnalite"=>"Création des demandes","unique_key_string"=>"demandes.create","fonctionnalite_parent"=>14,"modules_id"=>2], //22
            ["lib_fonctionnalite"=>"Voir les détails des demandes","unique_key_string"=>"demandes.details","fonctionnalite_parent"=>14,"modules_id"=>2], //23
            ["lib_fonctionnalite"=>"Impression fiche demande","unique_key_string"=>"demandes.print","fonctionnalite_parent"=>14,"modules_id"=>2], //24
            ["lib_fonctionnalite"=>"Attribuer les demandes","unique_key_string"=>"demandes.grant","fonctionnalite_parent"=>14,"modules_id"=>2], //25
            ["lib_fonctionnalite"=>"Modifier les demande","unique_key_string"=>"demandes.edit","fonctionnalite_parent"=>14,"modules_id"=>2], //26
            ["lib_fonctionnalite"=>"Surpprimer les demandes","unique_key_string"=>"demandes.destroy","fonctionnalite_parent"=>14,"modules_id"=>2], //27
            ["lib_fonctionnalite"=>"Approuver les demandes","unique_key_string"=>"demandes.approve","fonctionnalite_parent"=>14,"modules_id"=>2], //28
            ["lib_fonctionnalite"=>"Envoyer les demandes au contentieux","unique_key_string"=>"demandes.contentieux.add","fonctionnalite_parent"=>14,"modules_id"=>2], //29
            ["lib_fonctionnalite"=>"Retirer les demandes du contentieux","unique_key_string"=>"demandes.contentieux.remove","fonctionnalite_parent"=>14,"modules_id"=>2], //30
            // Gestion des flux migratoires
            ["lib_fonctionnalite"=>"Gestion des flux migratoires","unique_key_string"=>"flux.menu","fonctionnalite_parent"=>null,"modules_id"=>3], //31
            ["lib_fonctionnalite"=>"Voir les données migratoires","unique_key_string"=>"flux.view","fonctionnalite_parent"=>31,"modules_id"=>3], //32
            ["lib_fonctionnalite"=>"Ajouter des données migratoires","unique_key_string"=>"flux.create","fonctionnalite_parent"=>31,"modules_id"=>3], //33
            ["lib_fonctionnalite"=>"Modifier des données migratoires","unique_key_string"=>"flux.edit","fonctionnalite_parent"=>31,"modules_id"=>3], //34
            ["lib_fonctionnalite"=>"Supprimer des données migratoires","unique_key_string"=>"flux.destroy","fonctionnalite_parent"=>31,"modules_id"=>3], //35
            //Gestion du réféntiel
            ["lib_fonctionnalite"=>"Paramettre Système","unique_key_string"=>"system.menu","fonctionnalite_parent"=>null,"modules_id"=>5], //36
            ["lib_fonctionnalite"=>"Voir le paramettre Système","unique_key_string"=>"system.view","fonctionnalite_parent"=>36,"modules_id"=>5], //37
            ["lib_fonctionnalite"=>"Voir les dictionnaires des données","unique_key_string"=>"dashboard","fonctionnalite_parent"=>null,"modules_id"=>5], //38
            ["lib_fonctionnalite"=>"Tableau de bord","unique_key_string"=>"dashboard.view","fonctionnalite_parent"=>38,"modules_id"=>5], //39,
            ["lib_fonctionnalite"=>"Effectuer une recherche avancée","unique_key_string"=>"demandes.search.advanced","fonctionnalite_parent"=>14,"modules_id"=>2], //40,
            ["lib_fonctionnalite"=>"Voir les demandes à imprimer","unique_key_string"=>"demandes.print.cards","fonctionnalite_parent"=>14,"modules_id"=>2], //40,
            // Impétrant
            // ["lib_fonctionnalite"=>"Voir les impétrants","unique_key_string"=>"demandes.impetrants.all","fonctionnalite_parent"=>14,"modules_id"=>2], //40,

        ];

        foreach ($fonctionnalites as $key ) {
            $fonc = Fonctionnalite::where("lib_fonctionnalite",$key["lib_fonctionnalite"])->first();
            if( !$fonc ) {
                Fonctionnalite::create($key);
            }
        }
        //permissions
        $role = Role::find(1);
        $permissions = Fonctionnalite::all();
        $role->fonctionnalites()->sync($permissions);
    }
}
