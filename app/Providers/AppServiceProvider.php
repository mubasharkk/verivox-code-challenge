<?php

namespace App\Providers;

use App\Services\CostCalculator\CostCalculatorService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CostCalculatorService::class,
            function (Application $app) {
                return new CostCalculatorService(
                    $app->make(config('tariffs.tariff_provider'))
                );
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
