<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quartier extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the arrondissement that owns the Quartier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function arrondissement(): BelongsTo
    {
        return $this->belongsTo(Arrondissement::class, 'arrondissements_id', 'id');
    }
}
