<?php

namespace App\Providers;

use App\TechnoDev\src\Classes\TechnoDev;
use Illuminate\Support\ServiceProvider;

class TechnoDevProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton("TechnoDev",function(){
            return new TechnoDev;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
