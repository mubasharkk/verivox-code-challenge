<?php

namespace App\Services\CostCalculator\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

class AnnualCost implements Arrayable
{

    public function __construct(
        private string $tariffName,
        private float $annualCost
    ) {
    }

    #[ArrayShape(['tariff_name' => "string", 'annual_cost' => "float"])]
    public function toArray(): array
    {
        return [
            'tariff_name' => $this->tariffName,
            'annual_cost' => $this->annualCost,
        ];
    }
}
