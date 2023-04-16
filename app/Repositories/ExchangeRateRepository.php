<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Collection
     */
    public function getExchangeRatesByCurrencyId(int $currencyId): Collection
    {
        return $this->model->newQuery()->join(
            $this->currencyTableName,
            "$this->currencyTableName.id",
            '=',
            "$this->exchangeRateName.to_currency_id"
        )
            ->where("$this->exchangeRateName.from_currency_id", $currencyId)
            ->select(
                "$this->exchangeRateName.rate",
                "$this->currencyTableName.code",
                "$this->currencyTableName.name",
            )
            ->get();
    }
}
