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

    public static function searchMatch(array $data): ?self
    {
        $seuil   = (int) env('SIMILARITY_THRESHOLD', 60);
        $targets = self::where('actif', true)->get();
        foreach ($targets as $target) {
            $result = \App\TechnoDev\src\Classes\IdentitySimilarityService::compareWithWatchlist($target, $data);
            if ($result['score'] >= $seuil) {
                return $target;
            }
        }
        return null;
    }
        }
