<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfractionPreuve extends Model
{
    protected $fillable = ['infraction_id', 'chemin_fichier', 'nom_original'];

    public function infraction()
    {
        return $this->belongsTo(Infraction::class);
    }
}