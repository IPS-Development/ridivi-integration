<?php

namespace IPS\Integration\Ridivi\Providers;

use Illuminate\Support\ServiceProvider;
use IPS\Ridivi\Integration\RidiviIntegrationService;

class RidiviServiceProvider extends ServiceProvider
{
    /**
     * Register services
     * @return void
     */
    public function register()
    {
        $this->app->singleton("ridivi.integrationservice", function ($app) {
            return new RidiviIntegrationService();
        });
    }

    /**
     * @return void
     */
    public function boot(){

    }
}