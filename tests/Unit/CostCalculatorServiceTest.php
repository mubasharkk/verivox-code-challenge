<?php

namespace Tests\Unit;

use App\Services\CostCalculator\CostCalculatorService;
use App\Services\TariffProviders\VerivoxTariffProvider;
use Mockery\MockInterface;
use Tests\TestCase;

class CostCalculatorServiceTest extends TestCase
{

    public function test_cost_calculator_service_with_consumption_3500()
    {
        $service = $this->app->make(CostCalculatorService::class);
        $annualCosts = $service->getAnnualCost(3500);

        $expected = [
            [
                "tariff_name"    => "Product A",
                "annual_cost"    => 830,
                "cost_breakdown" => [
                    "base"     => 60,
                    "consumed" => 770,
                    "total"    => 830,
                ],
            ],
            [
                "tariff_name"    => "Product B",
                "annual_cost"    => 800,
                "cost_breakdown" => [
                    "base"     => 800,
                    "consumed" => 0,
                    "total"    => 800,
                ],
            ],
        ];


        $this->assertEquals($expected, $annualCosts->toArray());
    }

    public function test_cost_calculator_service_with_consumption_4500()
    {
        $service = $this->app->make(CostCalculatorService::class);
        $annualCosts = $service->getAnnualCost(4500);

        $expected = [
            [
                "tariff_name"    => "Product A",
                "annual_cost"    => 1050,
                "cost_breakdown" => [
                    "base"     => 60,
                    "consumed" => 990,
                    "total"    => 1050,
                ],
            ],
            [
                "tariff_name"    => "Product B",
                "annual_cost"    => 950,
                "cost_breakdown" => [
                    "base"     => 800,
                    "consumed" => 150,
                    "total"    => 950,
                ],
            ],
        ];

        $this->assertEquals($expected, $annualCosts->toArray());
    }

    public function test_cost_calculator_service_with_mock_tariff_provider()
    {
        $baseCost = 25;
        $additionalCost = 12;
        $totalCost = ($baseCost * 12) + (($additionalCost / 100) * 3500);
        $mock = $this->mock(VerivoxTariffProvider::class,
            function (MockInterface $mock) use ($baseCost, $additionalCost) {
                $mock->shouldReceive('getTariffViaApi')->andReturn([
                    [
                        'name'              => 'Product Test',
                        'type'              => 1,
                        'baseCost'          => $baseCost,
                        'additionalKwhCost' => $additionalCost,
                    ],
                ]);
            }
        );

        $service = $this->app->make(CostCalculatorService::class, [$mock]);
        $annualCosts = $service->getAnnualCost(3500);

        $this->assertCount(1, $annualCosts);

        $expected = [
            [
                "tariff_name"    => "Product Test",
                "annual_cost"    => $totalCost,
                "cost_breakdown" => [
                    "base"     => $baseCost * 12,
                    "consumed" => ($additionalCost / 100) * 3500,
                    "total"    => $totalCost,
                ],
            ],
        ];

        $this->assertEquals($expected, $annualCosts->toArray());
    }

    public function test_cost_calculator_service_with_mock_tariff_provider_with_unknown_type()
    {
        $mock = $this->mock(VerivoxTariffProvider::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('getTariffViaApi')->andReturn([
                    [
                        'name'              => 'Product Test',
                        'type'              => 3,
                        'baseCost'          => 20,
                        'additionalKwhCost' => 50,
                    ],
                ]);
            }
        );

        $this->assertThrows(
            fn() => $this->app->make(CostCalculatorService::class, [$mock]),
            \InvalidArgumentException::class,
            "Product type `3` is not defined"
        );
    }
}
