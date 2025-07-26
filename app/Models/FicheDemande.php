<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FicheDemande extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'demande_id', 'id');
    }
}
