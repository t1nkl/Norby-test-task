<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetByCurrencyCodeForSpecificCurrencyRequest;
use App\Http\Requests\V1\GetByCurrencyCodeRequest;
use App\Http\Resources\V1\ExchangeRateResource;
use App\Http\Resources\V1\ExchangeRateSpecificResource;
use App\Services\CurrencyService;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ExchangeRateController extends Controller
{
    public function __construct(
        public readonly CurrencyService $currencyService,
        public readonly ExchangeRateService $service
    ) {
    }

    /**
     * @param  GetByCurrencyCodeRequest  $request
     * @return JsonResponse
     * @api GET /api/v1/currencies/{currency_code}/exchange-rate
     */
    public function getByCurrencyCode(GetByCurrencyCodeRequest $request): JsonResponse
    {
        $currencyCode = Arr::get($request->validated(), 'currency_code');
        $currency = $this->currencyService->getRepository()->getCurrencyByCode($currencyCode);
        if (!$currency) {
            return response()->json([
                'message' => "Currency with code {$currencyCode} not found",
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $exchangeRates = $this->service->getByCurrencyId($currency->id, $request->validated());
        if ($exchangeRates->isEmpty()) {
            return response()->json([
                'message' => "Exchange rates for currency with code {$currencyCode} not found",
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(
            ExchangeRateResource::collection($exchangeRates)
        );
    }

    /**
     * @param  GetByCurrencyCodeForSpecificCurrencyRequest  $request
     * @return JsonResponse
     * @api GET /api/v1/currencies/{currency_code}/exchange-rate/{specific_currency_code}
     */
    public function getByCurrencyCodeForSpecificCurrency(GetByCurrencyCodeForSpecificCurrencyRequest $request): JsonResponse
    {
        $currencyCode = Arr::get($request->validated(), 'currency_code');
        $currency = $this->currencyService->getRepository()->getCurrencyByCode($currencyCode);
        if (!$currency) {
            return response()->json([
                'message' => "Currency with code {$currencyCode} not found",
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $specificCurrencyCode = Arr::get($request->validated(), 'specific_currency_code');
        $specificCurrency = $this->currencyService->getRepository()->getCurrencyByCode($specificCurrencyCode);
        if (!$specificCurrency) {
            return response()->json([
                'message' => "Currency with code {$specificCurrencyCode} not found",
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(
            ExchangeRateSpecificResource::collection(
                $this->service->getByCurrencyIdAndSpecificCurrencyId($currency->id, $specificCurrency->id, $request->validated())
            )
        );
    }
}
