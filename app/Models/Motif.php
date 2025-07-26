<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motif extends Model
{
    use HasFactory;
    protected $table = 'motif_contentieuxes';

    public function contetieux(){
        return $this->hasMany(Contentieux::class, 'motifs_id', 'id');
    }
}
