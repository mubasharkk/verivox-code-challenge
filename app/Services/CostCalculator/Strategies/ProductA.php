<?php

namespace App\Services\CostCalculator\Strategies;

use JetBrains\PhpStorm\Pure;

class ProductA implements CostModelStrategy
{

    public function __construct(
        private string $name,
        private string $type,
        private float $baseCost,
        private float $additionalKwhCost
    ) {
    }

    public function get(string $attribute)
    {
        return $this->{$attribute};
    }

    public function getBaseCostByMonths(int $months): float
    {
        return $this->baseCost * $months;
    }

    public function getConsumedCost(int $consumptionKwh): float
    {
        return $consumptionKwh * $this->additionalKwhCost / 100;
    }

    #[Pure]
    public function calculateAnnualCost(int $consumptionKwh): float
    {
        return $this->getBaseCostByMonths(12)
            + $this->getConsumedCost($consumptionKwh);
    }
}
