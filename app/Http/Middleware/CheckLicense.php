<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use Closure;
use Illuminate\Http\Request;

/**
 * Vérification de licence — deux couches :
 *
 * 1. Locale (vault DB) : vérification rapide initiale.
 *    Si valide → on passe, le header backend confirmera sur chaque appel API.
 *
 * 2. Backend (X-Licence-Status header) : ApiClient vérifie le header
 *    sur chaque réponse API. Si expired → bloque.
 *    C'est le backend (VPS) qui fait autorité sur la licence.
 *
 * Pour bloquer le système : stop le backend OU mettre LICENCE_EXPIRES_AT
 * à une date passée dans le .env du backend.
 */
class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
        AppMetricsCollector::collect();

        if (!LicenseService::isMysqlRunning()) {
            return response()->view('errors.mysql-offline', [], 503);
        }

        $validation = LicenseService::validate();

        if (!$validation['valid']) {
            return redirect('/license/locked')
                ->with('reason', $validation['reason']);
        }

        if (isset($validation['license'])) {
            $daysRemaining = $validation['days_remaining'] ?? 0;
            if ($daysRemaining < 7 && $daysRemaining > 0) {
                session()->flash('warning', "Votre licence expire dans {$daysRemaining} jour(s)");
            }
            $request->merge(['license' => $validation['license']]);
        }

        return $next($request);
    }
}
