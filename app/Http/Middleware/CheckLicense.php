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
            $graceLeft     = $validation['grace_days_left'] ?? null;

            if ($graceLeft !== null) {
                session()->flash('danger', "Licence expirée — période de grâce : {$graceLeft} jour(s) restant(s). Renouvelez immédiatement.");
            } elseif ($daysRemaining < 7 && $daysRemaining > 0) {
                session()->flash('warning', "Votre licence expire dans {$daysRemaining} jour(s)");
            }

            $request->merge(['license' => $validation['license']]);
        }

        return $next($request);
    }
}
