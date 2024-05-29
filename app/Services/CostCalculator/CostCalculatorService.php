<?php

namespace App\Services\CostCalculator;

use App\Services\CostCalculator\DTOs\AnnualCost;
use App\Services\CostCalculator\Factories\CostModelFactory;
use App\Services\CostCalculator\Strategies\CostModelStrategy;
use App\Services\TariffProviders\TariffProvider;
use Illuminate\Support\Collection;

class CostCalculatorService
{

    private Collection $calculators;

    public function __construct(TariffProvider $tariffProvider)
    {
        $data = $tariffProvider->getTariffViaApi();

        $this->calculators = collect($data)->map(function ($tariff) {
            return CostModelFactory::get($tariff);
        });;
    }

    public function getAnnualCost(float $consumption): Collection
    {
        return $this->calculators->map(
            function (CostModelStrategy $calc) use ($consumption) {
                return new AnnualCost(
                    tariffName: $calc->get('name'),
                    annualCost: $calc->calculateAnnualCost($consumption),
                    costBreakDown: $calc->getCostBreakdown($consumption)
                );
            }
        );
    }

    private function getCalculators(): Collection
    {
        return $this->calculators;
    }
}
