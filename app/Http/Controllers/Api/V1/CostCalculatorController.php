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
        $request->validate([
            'consumption' => 'required|integer',
            'sort'        => 'string|in:asc,desc',
        ]);

        $costCalculators = $costCalculator->getAnnualCost(
            $request->get('consumption')
        );

        $sortedResults = match ($request->get('sort')) {
            'desc' => $costCalculators->sortByDesc('annualCost'),
            default => $costCalculators->sortBy('annualCost')
        };

        return response()->json(
            $sortedResults->values()->toArray()
        );
    }
}
