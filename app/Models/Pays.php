<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pays extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the flux for the Pays
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flux(): HasMany
    {
        return $this->hasMany(FluxMigratoire::class, 'pays_id', 'id');
    }

}
