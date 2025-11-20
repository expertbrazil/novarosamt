<?php

namespace App\Providers;

use App\Models\Settings;
use App\Models\EstadoMunicipio;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
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
        
        // Ajusta SMTP com base nos parâmetros em produção
        if (app()->environment('production') && Schema::hasTable('settings')) {
            $mailConfig = [];

            $smtpHost = Settings::get('smtp_host');
            $smtpPort = Settings::get('smtp_port');
            $smtpUsername = Settings::get('smtp_username');
            $smtpPassword = Settings::get('smtp_password');
            $smtpEncryption = Settings::get('smtp_encryption');
            $smtpFromAddress = Settings::get('smtp_from_address');
            $smtpFromName = Settings::get('smtp_from_name');

            if (!empty($smtpHost)) {
                $mailConfig['mail.mailers.smtp.host'] = $smtpHost;
            }
            if (!empty($smtpPort)) {
                $mailConfig['mail.mailers.smtp.port'] = (int) $smtpPort;
            }
            if (!empty($smtpUsername)) {
                $mailConfig['mail.mailers.smtp.username'] = $smtpUsername;
            }
            if (!empty($smtpPassword)) {
                $mailConfig['mail.mailers.smtp.password'] = $smtpPassword;
            }
            if (!empty($smtpEncryption)) {
                $mailConfig['mail.mailers.smtp.encryption'] = $smtpEncryption;
            }
            if (!empty($smtpFromAddress)) {
                $mailConfig['mail.from.address'] = $smtpFromAddress;
            }
            if (!empty($smtpFromName)) {
                $mailConfig['mail.from.name'] = $smtpFromName;
            }
            if (!empty($mailConfig)) {
                $mailConfig['mail.default'] = 'smtp';
                Config::set($mailConfig);
            }
        }

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
