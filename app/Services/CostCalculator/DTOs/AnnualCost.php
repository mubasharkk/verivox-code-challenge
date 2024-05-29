<?php

namespace App\Services\CostCalculator\DTOs;

use Illuminate\Contracts\Support\Arrayable;

class AnnualCost implements Arrayable
{

    public function __construct(
        public string $tariffName,
        public float $annualCost,
        public array $costBreakDown = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'tariff_name'    => $this->tariffName,
            'annual_cost'    => $this->annualCost,
            'cost_breakdown' => $this->costBreakDown,
        ];
    }
}
