<?php

namespace App\Providers;

use App\Models\Demande;
use App\Observers\DemandeObserver;
use Illuminate\Support\ServiceProvider;
use App\Foundation\SystemBootstrap;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        SystemBootstrap::boot();
        // Observer pour les demandes
        Demande::observe(DemandeObserver::class);

        // ========================================
        // DIRECTIVES BLADE POUR LES PERMISSIONS
        // ========================================
        
        /**
         * Directive @canDo('permission.name')
         * Vérifie si l'utilisateur a une permission spécifique
         */
        Blade::if('canDo', function ($permission) {
            // Si pas connecté, refuser
            if (!auth()->check()) {
                return false;
            }
            
            // SuperAdmin a toutes les permissions
            if (auth()->user()->role && auth()->user()->role->lib_role === 'SuperAdmin') {
                return true;
            }
            
            // Vérifier la permission
            return auth()->user()->hasPermission($permission);
        });

        /**
         * Directive @canDoAny(['perm1', 'perm2'])
         * Vérifie si l'utilisateur a AU MOINS UNE des permissions
         */
        Blade::if('canDoAny', function ($permissions) {
            if (!auth()->check()) {
                return false;
            }
            
            // SuperAdmin a toutes les permissions
            if (auth()->user()->role && auth()->user()->role->lib_role === 'SuperAdmin') {
                return true;
            }
            
            // Vérifier si l'utilisateur a au moins une permission
            foreach ($permissions as $permission) {
                if (auth()->user()->hasPermission($permission)) {
                    return true;
                }
            }
            
            return false;
        });

        /**
         * Directive @cannotDo('permission.name')
         * Vérifie si l'utilisateur N'A PAS une permission
         */
        Blade::if('cannotDo', function ($permission) {
            if (!auth()->check()) {
                return true; // Pas connecté = pas de permission
            }
            
            // SuperAdmin a toutes les permissions
            if (auth()->user()->role && auth()->user()->role->lib_role === 'SuperAdmin') {
                return false;
            }
            
            // Retourner l'inverse de hasPermission
            return !auth()->user()->hasPermission($permission);
        });
    }
}