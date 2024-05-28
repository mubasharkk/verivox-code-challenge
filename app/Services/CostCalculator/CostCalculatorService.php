<?php

namespace App\Services\CostCalculator;

use App\Services\CostCalculator\DTOs\AnnualCost;
use App\Services\CostCalculator\Factories\CostModelFactory;
use App\Services\CostCalculator\Strategies\CostModelStrategy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class CostCalculatorService
{

    private mixed $provider;

    private Collection $calculators;

    public function __construct()
    {
        $this->calculators = $this->getTariffDataFromProvider();
    }

    private function getTariffDataFromProvider(): Collection
    {
        $provider = new (Config::get('tariffs.tariff_provider'));

        $data = $provider->getTariffViaApi();

        return collect($data)->map(function ($tariff) {
            return CostModelFactory::get($tariff);
        });
    }

    public function getAnnualCost(float $consumption): Collection
    {
        return $this->calculators->map(
            function (CostModelStrategy $calc) use ($consumption) {
                return new AnnualCost(
                    tariffName: $calc->get('name'),
                    annualCost: $calc->calculateAnnualCost($consumption)
                );
            }
        );
    }

    private function getCalculators(): Collection
    {
        return $this->calculators;
    }
}
