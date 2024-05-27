<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CostCalculatorController extends Controller
{

    public function getAnnualEstimates(Request $request)
    {
        $data = $request->validate([
            'consumption' => 'required|integer',
        ]);

        return $data;
    }
}
