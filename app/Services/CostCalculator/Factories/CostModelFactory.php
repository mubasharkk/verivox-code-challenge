<?php

namespace App\Services\CostCalculator\Factories;

use App\Services\CostCalculator\Strategies\CostModelStrategy;
use App\Services\CostCalculator\Strategies\ProductA;
use App\Services\CostCalculator\Strategies\ProductB;

class CostModelFactory
{

    public static function get(array $settingsData): ?CostModelStrategy
    {
        return match ($settingsData['type']) {
            1 => new ProductA(...$settingsData),
            2 => new ProductB(...$settingsData),
            default => throw new \InvalidArgumentException(
                "Product type `{$settingsData['type']}` is not defined"
            )
        };
    }
}
