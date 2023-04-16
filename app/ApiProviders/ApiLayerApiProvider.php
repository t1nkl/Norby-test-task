<?php

namespace App\ApiProviders;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ApiLayerApiProvider
{
    public const API_URL = 'https://api.apilayer.com';

    /**
     * Returns all available currencies.
     */
    public const CURRENCY_CODES_ENDPOINT = '/exchangerates_data/symbols';

    /**
     * Returns historical data for a specific currency.
     */
    public const CURRENCY_CODE_HISTORY_ENDPOINT = '/exchangerates_data/timeseries';

    /**
     * @return array
     */
    public function getCurrencyCodes(): array
    {
        $response = Http::withHeaders([
            'apikey' => config('services.apilayer.key'),
            'Content-Type: text/plain',
        ])
            ->connectTimeout(5)
            ->acceptJson()
            ->get(self::API_URL . self::CURRENCY_CODES_ENDPOINT)
            ->json();

        if (Arr::get($response, 'success', false)) {
            return Arr::get($response, 'symbols', []);
        }

        return [];
    }

    /**
     * @param  string  $code
     * @param  string|null  $dateFrom
     * @param  string|null  $dateTo
     * @return array
     */
    public function getCurrencyCodeHistory(string $code, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $response = Http::withHeaders([
            'apikey' => config('services.apilayer.key'),
            'Content-Type: text/plain',
        ])
            ->connectTimeout(5)
            ->acceptJson()
            ->get(self::API_URL . self::CURRENCY_CODE_HISTORY_ENDPOINT, [
                'currencies' => $code,
                'start_date' => $dateFrom ?? now()->subDays(30)->format('Y-m-d'),
                'end_date' => $dateTo ?? now()->format('Y-m-d'),
            ])
            ->json();

        if (Arr::get($response, 'success', false) && Arr::get($response, 'timeseries', false)) {
            return Arr::get($response, 'rates', []);
        }

        return [];
    }
}
