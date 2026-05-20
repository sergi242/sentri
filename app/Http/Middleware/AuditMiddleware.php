<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class AuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // On ne log pas les assets, livewire, debugbar, health-check
      if (
    $request->is('*.css') ||
    $request->is('*.js') ||
    $request->is('*.png') ||
    $request->is('*.jpg') ||
    $request->is('*.svg') ||
    $request->is('*.ico') ||
    $request->is('*.woff*') ||
    $request->is('_debugbar/*') ||
    $request->is('_ignition/*') ||
    $request->is('sanctum/*') ||
    $request->is('ignition/*') ||
    $request->is('monitor/feed') ||
    $request->is('monitor/users')
)       {
            return $response;
        }

        $user = Auth::user();
$geo = null;

try {
    $geo = Http::timeout(2)->get('http://ip-api.com/json/'.$request->ip())->json();
} catch (\Exception $e) {}

        try {
            AuditLog::create([
                'user_id'      => $user?->id,
                'user_name'    => $user ? $user->prenom . ' ' . $user->nom : 'Invité',
                'user_role'    => $user?->role?->lib_role ?? null,
                'action'       => $request->method(),
                'action_label' => $this->resolveActionLabel($request),
                'module'       => $this->detectModule($request->path()),
                'entity_type'  => null,
                'entity_id'    => $this->detectEntityId($request),
                'old_values'   => null,
                'new_values'   => $request->method() !== 'GET'
                    ? $request->except(['password', '_token', 'password_confirmation'])
                    : null,
                'status'       => $this->resolveStatus($response),
                'ip_address'   => $request->ip(),
                'user_agent'   => substr($request->userAgent(), 0, 255),
                'route'        => optional($request->route())->getName(),
                'method'       => $request->method(),
                'url'          => $request->path(),
                'country' => $geo['country'] ?? null,
                'city'    => $geo['city'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Ne pas bloquer l'app si le log échoue
        }

        return $response;
    }

    private function resolveStatus(Response $response): string
    {
        return match(true) {
            $response->getStatusCode() === 403 => 'forbidden',
            $response->getStatusCode() >= 400  => 'failed',
            default                             => 'success',
        };
    }

    private function resolveActionLabel(Request $request): string
    {
        $route  = optional($request->route())->getName() ?? '';
        $method = $request->method();
        $path   = $request->path();

        // Actions spécifiques basées sur la route
        if (str_contains($route, 'store') || ($method === 'POST' && !str_contains($path, 'check')))
            return 'Création';
        if (str_contains($route, 'update') || in_array($method, ['PUT', 'PATCH']))
            return 'Modification';
        if (str_contains($route, 'destroy') || $method === 'DELETE')
            return 'Suppression';
        if (str_contains($route, 'approve') || str_contains($path, 'changestate'))
            return 'Approbation';
        if (str_contains($route, 'contentieux') || str_contains($path, 'contentieux'))
            return 'Contentieux';
        if (str_contains($route, 'watchlist'))
            return 'Watchlist';
        if (str_contains($path, 'authenticate'))
            return 'Connexion';
        if (str_contains($path, 'logout'))
            return 'Déconnexion';
        if (str_contains($path, 'attribution') || str_contains($path, 'remplirformation'))
            return 'Attribution';
        if (str_contains($path, 'archiv'))
            return 'Archivage';
        if (str_contains($path, 'soit-transmis'))
            return 'Soit-Transmis';
        if (str_contains($path, 'flux'))
            return 'Flux Migratoire';
        if (str_contains($path, 'generate-pdf') || str_contains($path, 'pdf'))
            return 'Impression PDF';
        if ($method === 'GET')
            return 'Consultation';

        return 'Action';
    }

    private function detectModule(string $path): string
    {
        $segment = explode('/', $path)[0] ?? 'system';

        return match($segment) {
            'demandes'      => 'demandes',
            'impetrants'    => 'impetrants',
            'flux'          => 'flux',
            'frontieres'    => 'frontieres',
            'watchlist'     => 'watchlist',
            'archives', 'archivage' => 'archives',
            'soit-transmis' => 'soit-transmis',
            'reporting'     => 'reporting',
            'users'         => 'users',
            'roles'         => 'roles',
            'employeurs'    => 'employeurs',
            'authenticate'  => 'authenticate',
            'dashboard'     => 'dashboard',
            default         => $segment,
        };
    }

    private function detectEntityId(Request $request): ?int
    {
        $route = $request->route();
        if (!$route) return null;

        foreach (['id', 'demande', 'impetrant', 'user', 'watchlist'] as $param) {
            $val = $route->parameter($param);
            if ($val) return is_numeric($val) ? (int)$val : null;
        }
        return null;
    }
}