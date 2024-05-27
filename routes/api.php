<?php

use App\Http\Controllers\Api\V1\CostCalculatorController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::get('/', fn() => ['version' => '1.0.0']);

    Route::get('/consumption', [
        CostCalculatorController::class,
        'getAnnualEstimates',
    ]);
});
