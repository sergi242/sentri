<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fonctionnalite extends Model
{
    use HasFactory;

    /**
     * Get the module that owns the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'modules_id', 'id');
    }

    /**
     * Get all of the enfants for the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enfants(): HasMany
    {
        return $this->hasMany(Fonctionnalite::class, 'fonctionnalite_parent', 'id');
    }

    /**
     * Get the parent that owns the Fonctionnalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Fonctionnalite::class, 'fonctionnalite_parent', 'id');
    }
}
