<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The fonctionnalites that belong to the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fonctionnalites(): BelongsToMany
    {
        return $this->belongsToMany(Fonctionnalite::class, 'roles_fonctionnalites', 'roles_id', 'fonctionnalites_id')->withTimestamps();
    }

    public function permissions_strings(){
        return $this->fonctionnalites->map->lib_fonctionnalite;
    }
}
