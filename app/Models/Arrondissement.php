<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arrondissement extends Model
{
    use HasFactory;
    //use SoftDeletes;
    protected $guarded = [];

    /**
     * Get the departement that owns the Arrondissement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departements_id', 'id');
    }

    /**
     * Get all of the quartiers for the Arrondissement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quartiers(): HasMany
    {
        return $this->hasMany(Quartier::class, 'arrondissements_id', 'id');
    }

}
