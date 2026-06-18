<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use Closure;
use Illuminate\Http\Request;

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
                session()->flash('warning', "Votre licence expire dans {$daysRemaining} jours");
            }
            if ($daysRemaining <= 0) {
                return redirect('/license/locked')
                    ->with('reason', 'Votre licence a expiré');
            }
            $request->merge(['license' => $validation['license']]);
        }

        return $next($request);
    }
}
