<?php

namespace App\Services\TariffProviders;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class VerivoxTariffProvider implements TariffProvider
{

    private const API_ENDPOINT = 'https://verivox.com/tariffs/data.json';

    public function getTariffViaApi(): array
    {
        try {
            $response = $this->getResponse();

            return $response->json();
        } catch (\Exception $ex) {
            return [];
        }
    }

    private function getResponse(): Response
    {
        Http::fake([
            // Fake a JSON Tariff response from verivox.com
            self::API_ENDPOINT => Http::response([
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
                // ... You can add more tariff's data here!
            ]),
        ]);

        return Http::get(self::API_ENDPOINT);
    }
}
