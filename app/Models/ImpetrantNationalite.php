<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImpetrantNationalite extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the pays that owns the ImpetrantNationalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id', 'id');
    }

    /**
     * Get the impetrant that owns the ImpetrantNationalite
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function impetrant(): BelongsTo
    {
        return $this->belongsTo(Impetrant::class, 'impetrant_id', 'id');
    }
}
