<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ExchangeRateResource;
use App\Services\CurrencyService;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ExchangeRateController extends Controller
{
    public function __construct(
        public readonly CurrencyService $currencyService,
        public readonly ExchangeRateService $service
    ) {
    }

    /**
     * @param  Request  $request
     * @param  string  $currencyCode
     * @return JsonResponse
     */
    public function getExchangeRateByCurrencyCode(Request $request, string $currencyCode): JsonResponse
    {
        $currency = $this->currencyService->getRepository()->getCurrencyByCode($currencyCode);
        if (!$currency) {
            return response()->json([
                'message' => 'Currency not found',
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $exchangeRates = $this->service->getRepository()->getExchangeRatesByCurrencyId($currency->id);
        if ($exchangeRates->isEmpty()) {
            return response()->json([
                'message' => 'Exchange rate not found',
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(
            ExchangeRateResource::collection($exchangeRates)
        );
    }
}
