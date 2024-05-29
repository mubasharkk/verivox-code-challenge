<?php

namespace App\Services\CostCalculator\Strategies;

class ProductB implements CostModelStrategy
{

    public function __construct(
        private string $name,
        private string $type,
        private float $baseCost,
        private float $additionalKwhCost,
        private int $includedKwh = 0
    ) {
    }

    public function get(string $attribute)
    {
        return $this->{$attribute};
    }

    public function calculateAnnualCost(int $consumptionKwh): float
    {
        $totalCost = $this->baseCost;

        if ($consumptionKwh > $this->includedKwh) {
            $totalCost += ($consumptionKwh - $this->includedKwh)
                * ($this->additionalKwhCost / 100);
        }

        return $totalCost;
    }

    public function getCostBreakdown(int $consumptionKwh): array
    {
        $annualCost = $this->calculateAnnualCost($consumptionKwh);

        return [
            'base'     => $this->baseCost,
            'consumed' => $annualCost - $this->baseCost,
            'total'    => $annualCost,
        ];
    }
}
