<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }

        if (!auth()->user()->hasPermission($permission)) {
            toastr()->error('❌ Vous n\'avez pas la permission d\'accéder à cette page.');
            return redirect()->back();
        }

        return $next($request);
    }
}