<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_sessions';

    protected $fillable = [
        'user_id', 'user_name', 'user_role',
        'ip_address', 'user_agent',
        'login_at', 'logout_at',
        'duration_seconds', 'status',
    ];

    protected $casts = [
        'login_at'  => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationLabelAttribute(): string
    {
        $s = $this->duration_seconds ?? 0;
        if ($s < 60)   return $s . 's';
        if ($s < 3600) return intdiv($s, 60) . 'min ' . ($s % 60) . 's';
        return intdiv($s, 3600) . 'h ' . intdiv(($s % 3600), 60) . 'min';
    }
}
