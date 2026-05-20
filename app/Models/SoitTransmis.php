<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoitTransmis extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    /**
     * Relation avec les demandes
     */
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'soit_transmis_id');
    }
    
    /**
     * Relation avec l'utilisateur créateur
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Relation avec le commanditaire (utilisateur)
     */
    public function commanditaire()
    {
        return $this->belongsTo(User::class, 'commanditaire_id');
    }
    
    /**
     * Relation avec l'utilisateur assigné (users_id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}