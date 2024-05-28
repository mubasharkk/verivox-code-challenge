<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\CostCalculator\CostCalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CostCalculatorController extends Controller
{

    public function getAnnualEstimates(
        Request $request,
        CostCalculatorService $costCalculator,
    ): JsonResponse {
        $data = $request->validate([
            'consumption' => 'required|integer',
        ]);

        $data = $costCalculator->getAnnualCost($data['consumption']);

        return response()->json($data);
    }
}
