<?php

namespace Tests\Unit;

use App\Services\CostCalculator\Strategies\ProductA;
use App\Services\CostCalculator\Strategies\ProductB;
use Monolog\Test\TestCase;

class TariffProductsTest extends TestCase
{

    public function test_product_A_with_tariff_data()
    {
        $product = new ProductA(
            name: 'Product A',
            type: 1,
            baseCost: 10,
            additionalKwhCost: 35
        );

        $this->assertEquals(
            [
                'base'     => round(10 * 12, 2),
                'consumed' => round(80 * 0.35, 2),
                'total'    => (10 * 12) + (80 * 0.35),
            ],
            $product->getCostBreakdown(80)
        );

        $this->assertEquals(1, $product->get('type'));
    }


    public function test_product_B_with_tariff_data()
    {
        $product = new ProductB(
            name: 'Product B',
            type: 2,
            baseCost: 100,
            additionalKwhCost: 35,
            includedKwh: 75
        );

        $this->assertEquals(
            [
                'base'     => 100,
                'consumed' => round(5 * 0.35, 2),
                'total'    => 100 + (5 * 0.35),
            ],
            $product->getCostBreakdown(80)
        );

        $this->assertEquals(2, $product->get('type'));

        $product = new ProductB(
            name: 'Product B',
            type: 2,
            baseCost: 100,
            additionalKwhCost: 35,
            includedKwh: 80
        );

        $this->assertEquals(
            [
                'base'     => 100,
                'consumed' => 0,
                'total'    => 100 + 0,
            ],
            $product->getCostBreakdown(80)
        );

        $this->assertEquals(2, $product->get('type'));
    }
}
