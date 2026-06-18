<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Redirige vers login si non authentifié.
     * Identique à l'original.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * AJOUT : après vérification auth standard,
     * expulse immédiatement tout agent dont active = 0.
     * Couvre le cas où un compte est désactivé APRÈS connexion.
     */
    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, $guards);

        if (Auth::check() && (int) Auth::user()->active === 0) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            redirect()->route('login')
                ->with('error_inactive', 'Votre compte a été désactivé. Contactez un administrateur.')
                ->send();
            exit;
        }
    }
}
