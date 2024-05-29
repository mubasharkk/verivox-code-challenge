<?php

namespace App\Services\CostCalculator\Strategies;

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

    public function calculateAnnualCost(int $consumptionKwh): float
    {
        return $this->getBaseCostByMonths(12)
            + $this->getConsumedCost($consumptionKwh);
    }

    public function getCostBreakdown(int $consumptionKwh): array
    {
        return [
            'total'    => $this->calculateAnnualCost($consumptionKwh),
            'base'     => $this->getBaseCostByMonths(12),
            'consumed' => $this->getConsumedCost($consumptionKwh),
        ];
    }
}
