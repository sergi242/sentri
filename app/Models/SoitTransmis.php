<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoitTransmis extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function demandes() {
        return $this->hasMany(Demande::class);
    }
    public function users() {
        return $this->belongsTo(User::class);
    }
    public function commanditaire() {
        return $this->belongsTo(User::class);
    }
}
