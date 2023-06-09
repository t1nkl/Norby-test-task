<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CurrencyResource;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        public readonly CurrencyService $currencyService
    ) {
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @api GET /api/v1/currencies
     */
    public function getCurrencies(Request $request): JsonResponse
    {
        return response()->json(
            CurrencyResource::collection(
                $this->currencyService->getRepository()->all()
            )
        );
    }
}
