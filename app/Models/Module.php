<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['lib_module'];

    public function fonctionnalites()
    {
        return $this->hasMany(Fonctionnalite::class, 'modules_id');
    }
}