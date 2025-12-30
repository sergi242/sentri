<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentDemande extends Model
{
    use HasFactory;

    /**
     * Get the demande that owns the DocumentDemande
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function demande(): BelongsTo
    {
        return $this->belongsTo(Demande::class, 'demandes_id', 'id');
    }
}
