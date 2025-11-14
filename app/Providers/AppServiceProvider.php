<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

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
        Schema::defaultStringLength(191);
        
        // Definir timezone padrão para Carbon
        $timezone = config('app.timezone', 'America/Sao_Paulo');
        Carbon::setLocale('pt_BR');
        date_default_timezone_set($timezone);
        
        // Share settings with all views
        View::composer('*', function ($view) {
            $view->with('settings', Settings::getAll());
        });
    }
}
