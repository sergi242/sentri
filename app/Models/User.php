<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\EncryptCast;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        //'email'=>EncryptCast::class
    ];

    /**
     * Get the role that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roles_id', 'id');
    }

    /**
     * Get the grade that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grades_id', 'id');
    }

    public function getNomPrenom(): string
    {
        return strtoupper($this->nom) . " " . $this->prenom;
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'created_by');
    }

    public function soitTransmis()
    {
        return $this->hasMany(SoitTransmis::class, 'created_by'); // Adapte si nécessaire
    }

    public function fluxMigratoires()
    {
        return $this->hasMany(FluxMigratoire::class, 'users_id', 'id');
    }
 /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public function hasPermission($permissionKey)
    {
        // SuperAdmin a toutes les permissions
        if ($this->role && $this->role->lib_role === 'SuperAdmin') {
            return true;
        }

        // Vérifier si le rôle a cette permission
        return DB::table('roles_fonctionnalites as rf')
            ->join('fonctionnalites as f', 'f.id', '=', 'rf.fonctionnalites_id')
            ->where('rf.roles_id', $this->roles_id)
            ->where('f.unique_key_string', $permissionKey)
            ->exists();
    }

    /**
     * Vérifier si l'utilisateur a au moins une des permissions
     */
    public function hasAnyPermission(array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier si l'utilisateur a toutes les permissions
     */
    public function hasAllPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
}
