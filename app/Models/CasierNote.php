<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasierNote extends Model
{
    protected $fillable = ['impetrant_id', 'user_id', 'note', 'niveau'];

    public function impetrant()
    {
        return $this->belongsTo(Impetrant::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}