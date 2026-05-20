<?php

namespace App\Listeners;

use App\Models\UserSession;
use Illuminate\Auth\Events\Login;

class TrackUserLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Fermer toute session active précédente
        UserSession::where('user_id', $user->id)
            ->where('status', 'active')
            ->each(function ($session) {
                $duration = $session->login_at
                    ? now()->diffInSeconds($session->login_at)
                    : 0;
                $session->update([
                    'logout_at'        => now(),
                    'duration_seconds' => $duration,
                    'status'           => 'expired',
                ]);
            });

        // Créer nouvelle session
        UserSession::create([
            'user_id'    => $user->id,
            'user_name'  => $user->prenom . ' ' . $user->nom,
            'user_role'  => $user->role?->lib_role,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent(), 0, 255),
            'login_at'   => now(),
            'status'     => 'active',
        ]);
    }
}
