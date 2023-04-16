<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExchangeRateRepository
{
    private ExchangeRate $model;
    private string $currencyTableName;
    private string $exchangeRateName;

    public function __construct()
    {
        $this->model = new ExchangeRate();

        $this->currencyTableName = Currency::getTableName();
        $this->exchangeRateName = ExchangeRate::getTableName();
    }

    /**
     * @param  int  $currencyId
     * @param  string|null  $dateFrom
     * @param  string|null  $dateTo
     * @return LengthAwarePaginator
     */
    public function getByCurrencyId(int $currencyId, ?string $dateFrom = null, ?string $dateTo = null): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->join(
                $this->currencyTableName,
                "$this->currencyTableName.id",
                '=',
                "$this->exchangeRateName.to_currency_id"
            )
            ->where('from_currency_id', $currencyId)
            ->select(
                "$this->currencyTableName.code",
                "$this->currencyTableName.name",
                "$this->exchangeRateName.rate",
                "$this->exchangeRateName.created_at"
            );

        if ($dateFrom) {
            $query->where("$this->exchangeRateName.created_at", '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where("$this->exchangeRateName.created_at", '<=', $dateTo);
        }

        return $query->paginate(100);
    }

    /**
     * @param  int  $currencyId
     * @param  int  $specificCurrencyId
     * @param  string|null  $dateFrom
     * @param  string|null  $dateTo
     * @return LengthAwarePaginator
     */
    public function getByCurrencyIdAndSpecificCurrencyId(
        int $currencyId,
        int $specificCurrencyId,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): LengthAwarePaginator {
        $query = $this->model->newQuery()
            ->where('from_currency_id', $currencyId)
            ->where('to_currency_id', $specificCurrencyId)
            ->select(
                "$this->exchangeRateName.rate",
                "$this->exchangeRateName.created_at"
            );

        if ($dateFrom) {
            $query->where("$this->exchangeRateName.created_at", '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where("$this->exchangeRateName.created_at", '<=', $dateTo);
        }

        return $query->paginate(100);
    }
}
