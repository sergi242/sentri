<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

if (!function_exists('logActivity')) {

    function logActivity(
        string $action,
        string $module,
        string $description
    ): void {
        try {
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'module'     => $module,
                'description'=> $description,
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // On ne casse JAMAIS l’app à cause d’un log
        }
    }
}
