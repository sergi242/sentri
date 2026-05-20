<?php

namespace App\Listeners;

use App\Models\UserSession;
use Illuminate\Auth\Events\Logout;

class TrackUserLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;
        if (!$user) return;

        UserSession::where('user_id', $user->id)
            ->where('status', 'active')
            ->each(function ($session) {
                $duration = $session->login_at
                    ? now()->diffInSeconds($session->login_at)
                    : 0;
                $session->update([
                    'logout_at'        => now(),
                    'duration_seconds' => $duration,
                    'status'           => 'logout',
                ]);
            });
    }
}
