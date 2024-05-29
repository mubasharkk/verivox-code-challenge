<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiEndpointTest extends TestCase
{

    public function test_health_check(): void
    {
        $response = $this->get('/api/v1/');
        $response->assertStatus(200);
        $response->assertJson(['version' => '1.0.0']);
    }

    public function test_api_endpoint_for_consumption_calc_with_invalid_args()
    {
        $response = $this->get('/api/v1/calculate-consumption');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'consumption' => ['The consumption field is required.'],
            ],
        ]);
    }

    public function test_api_endpoint_for_consumption_calculations_having_consumption_3500(): void
    {
        $response = $this->get(
            '/api/v1/calculate-consumption?'
            .http_build_query(['consumption' => 3500, 'sort' => 'desc'])
        );
        $response->assertStatus(200);

        $response->assertJson([
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
        ]);
    }

    public function test_api_endpoint_for_consumption_calculations_having_consumption_4500(): void
    {
        $response = $this->get(
            '/api/v1/calculate-consumption?'
            .http_build_query(['consumption' => 4500, 'sort' => 'desc'])
        );
        $response->assertStatus(200);

        $response->assertJson([
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
        ]);
    }
}
