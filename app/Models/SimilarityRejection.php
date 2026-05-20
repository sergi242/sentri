<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimilarityRejection extends Model
{
    protected $fillable = [
        'demande_base_id',
        'demande_similaire_id',
        'user_id',
    ];

    // 🔹 Demande principale
    public function baseDemande()
    {
        return $this->belongsTo(Demande::class, 'demande_base_id');
    }

    // 🔹 Demande similaire
    public function similaireDemande()
    {
        return $this->belongsTo(Demande::class, 'demande_similaire_id');
    }

    // 🔹 Utilisateur qui a rejeté
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
