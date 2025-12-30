<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Impetrant extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function nomcomplet(){
        return $this->nom ." ".$this->prenom;
    }

    /**
     * Get all of the demandes for the Impetrant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function demandes(): HasMany
    {
        return $this->hasMany(Demande::class, 'impetrants_id', 'id');
    }

    public function renouvellements(){
        return $this->demandes->where("impetrants_id",$this->id)->count() > 1;
    }

    /**
     * Get the pays that owns the Impetrant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'nationalites_id', 'id');
    }

    /**
     * Get all of the nationalites for the Impetrant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nationalites(): HasMany
    {
        return $this->hasMany(ImpetrantNationalite::class, 'impetrant_id', 'id');
    }
}
