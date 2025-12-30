<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;
    //use SoftDeletes;
    protected $guarded = [];

    /**
     * Get all of the arrondissements for the Departement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function arrondissements(): HasMany
    {
        return $this->hasMany(Arrondissement::class, 'departements_id', 'id');
    }

    /**
     * Get all of the frontieres for the Departement
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function frontieres(): HasMany
    {
        return $this->hasMany(FrontiereCongo::class, 'departements_id', 'id');
    }

}
