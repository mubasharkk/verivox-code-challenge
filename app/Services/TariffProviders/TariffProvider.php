<?php

namespace App\Services\TariffProviders;

interface TariffProvider
{

    public function getTariffViaApi(): array;
}
