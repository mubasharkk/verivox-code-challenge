<?php

namespace App\Services\CostCalculator\Strategies;

interface CostModelStrategy
{

    public function calculateAnnualCost(int $consumptionKwh): float;

    public function get(string $attribute);

    public function getCostBreakdown(int $consumptionKwh): array;
}
