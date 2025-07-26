<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\EncryptCast;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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



}
