<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FluxMigratoire extends Model
{
    use HasFactory;

    /**
     * Get the frontiere that owns the FluxMigratoire
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function frontiere(): BelongsTo
    {
        return $this->belongsTo(FrontiereCongo::class, 'frontieres_id', 'id');
    }

    /**
     * Get the pays that owns the FluxMigratoire
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pays_id', 'id');
    }
}
