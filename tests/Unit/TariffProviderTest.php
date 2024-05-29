<?php

namespace Tests\Unit;

use App\Services\TariffProviders\TariffProvider;
use Tests\TestCase;

class TariffProviderTest extends TestCase
{
    public function test_tariff_provider_initialization_via_config()
    {
        $provider = $this->app->make(config('tariffs.tariff_provider'));

        $this->assertTrue($provider instanceof TariffProvider);
    }

    public function test_tariff_provider_get_api_response()
    {
        /** @var TariffProvider $provider */
        $provider = $this->app->make(config('tariffs.tariff_provider'));

        $data = $provider->getTariffViaApi();

        $this->assertEquals([
            [
                'name'              => 'Product A',
                'type'              => 1,
                'baseCost'          => 5,
                'additionalKwhCost' => 22,
            ],
            [
                'name'              => 'Product B',
                'type'              => 2,
                'includedKwh'       => 4000,
                'baseCost'          => 800,
                'additionalKwhCost' => 30,
            ]
        ], $data);
    }
}
