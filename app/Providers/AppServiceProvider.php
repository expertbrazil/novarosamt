<?php

namespace App\Providers;

use App\Models\Settings;
use App\Models\EstadoMunicipio;
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
            $settings = Settings::getAll();
            $view->with('settings', $settings);
            
            // Buscar cidades de entrega para o footer
            $deliveryCities = collect([]);
            $deliveryCitiesJson = Settings::get('delivery_cities', '[]');
            if ($deliveryCitiesJson) {
                $decoded = json_decode($deliveryCitiesJson, true) ?? [];
                if (is_array($decoded)) {
                    foreach ($decoded as $cityData) {
                        $municipioId = $cityData['municipio_id'] ?? $cityData;
                        $municipio = EstadoMunicipio::find($municipioId);
                        if ($municipio) {
                            $deliveryCities->push($municipio);
                        }
                    }
                }
            }
            $view->with('footerDeliveryCities', $deliveryCities);
        });
    }
}
