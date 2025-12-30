<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contentieux extends Model
{
    use HasFactory;
    protected $table = 'contentieuxes';

    public function demande()
    {
        
        return $this->belongsTo(Demande::class, 'demandes_id', 'id');
    }

    public function motif()
    {
        return $this->belongsTo(Motif::class, 'motifs_id', 'id');
    }
}
