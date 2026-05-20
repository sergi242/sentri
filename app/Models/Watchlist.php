<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function impetrant()
    {
        return $this->belongsTo(Impetrant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'impetrant_id',

        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'pays_naissance',
        'nationalite',
        'sexe',
        'age_min',
        'age_max',  

        'nom_pere',
        'prenom_pere',
        'nom_mere',
        'prenom_mere',

        'etat_matrimonial',
        'profession',
        'adresse',
        'telephone',

        'numero_document',
        'photo_profil',

        'motif_alerte',
        'niveau_danger',
        'actif'
    ];

    /*
    |--------------------------------------------------------------------------
    | MATCHING INTELLIGENT
    |--------------------------------------------------------------------------
    */

    public static function searchMatch(array $data)
    {
        $targets = self::where('actif', true)->get();

        $seuilAlerte = 75; // ajustable

        foreach ($targets as $target) {

            $score = 0;
            $weightTotal = 0;

            /*
            |--------------------------------------------------------------------------
            | 1. Match prioritaire : numéro document
            |--------------------------------------------------------------------------
            */
            if (!empty($target->numero_document) && !empty($data['numero_passeport'])) {

                if (strtoupper(trim($target->numero_document)) === strtoupper(trim($data['numero_passeport']))) {
                    return $target; // match direct
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Nom (poids 30)
            |--------------------------------------------------------------------------
            */
            if (!empty($target->nom) && !empty($data['nom'])) {

                similar_text(
                    strtoupper(trim($target->nom)),
                    strtoupper(trim($data['nom'])),
                    $percentNom
                );

                $score += ($percentNom * 0.30);
                $weightTotal += 30;
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Prénom (poids 20)
            |--------------------------------------------------------------------------
            */
            if (!empty($target->prenom) && !empty($data['prenom'])) {

                similar_text(
                    strtoupper(trim($target->prenom)),
                    strtoupper(trim($data['prenom'])),
                    $percentPrenom
                );

                $score += ($percentPrenom * 0.20);
                $weightTotal += 20;
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Date naissance (poids 30)
            |--------------------------------------------------------------------------
            */
            if (!empty($target->date_naissance) && !empty($data['date_naissance'])) {

                if ($target->date_naissance == $data['date_naissance']) {
                    $score += 30;
                }

                $weightTotal += 30;
            }

            /*
            |--------------------------------------------------------------------------
            | 5. Nom père (poids 10)
            |--------------------------------------------------------------------------
            */
            if (!empty($target->nom_pere) && !empty($data['nom_pere'])) {

                if (strtoupper($target->nom_pere) === strtoupper($data['nom_pere'])) {
                    $score += 10;
                }

                $weightTotal += 10;
            }

            /*
            |--------------------------------------------------------------------------
            | 6. Nom mère (poids 10)
            |--------------------------------------------------------------------------
            */
            if (!empty($target->nom_mere) && !empty($data['nom_mere'])) {

                if (strtoupper($target->nom_mere) === strtoupper($data['nom_mere'])) {
                    $score += 10;
                }

                $weightTotal += 10;
            }

            /*
            |--------------------------------------------------------------------------
            | SCORE FINAL
            |--------------------------------------------------------------------------
            */
            if ($weightTotal > 0) {

                $finalScore = $score;

                if ($finalScore >= $seuilAlerte) {
                    return $target;
                }
            }
        }

      
        return null;
    }
    
}