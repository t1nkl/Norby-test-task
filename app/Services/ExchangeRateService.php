<?php

namespace App\Services;

use App\Repositories\ExchangeRateRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class ExchangeRateService
{
    public function __construct(public ExchangeRateRepository $repository)
    {
    }

    /**
     * @return ExchangeRateRepository
     */
    public function getRepository(): ExchangeRateRepository
    {
        return $this->repository;
    }

    /**
     * @param  int  $currencyId
     * @param  array  $validated
     * @return LengthAwarePaginator
     */
    public function getByCurrencyId(int $currencyId, array $validated): LengthAwarePaginator
    {
        return $this->getRepository()->getByCurrencyId(
            $currencyId,
            Arr::get($validated, 'date_from'),
            Arr::get($validated, 'date_to')
        );
    }

    /**
     * @param  int  $currencyId
     * @param  int  $specificCurrencyId
     * @param  array  $validated
     * @return LengthAwarePaginator
     */
    public function getByCurrencyIdAndSpecificCurrencyId(int $currencyId, int $specificCurrencyId, array $validated): LengthAwarePaginator
    {
        return $this->getRepository()->getByCurrencyIdAndSpecificCurrencyId(
            $currencyId,
            $specificCurrencyId,
            Arr::get($validated, 'date_from'),
            Arr::get($validated, 'date_to')
        );
    }
}
