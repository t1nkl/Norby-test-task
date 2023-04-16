<?php

namespace App\Repositories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepository
{
    private Currency $model;

    public function __construct()
    {
        $this->model = new Currency();
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->newQuery()->get();
    }

    /**
     * @param  string  $code
     * @return Currency|null
     */
    public function getCurrencyByCode(string $code): ?Currency
    {
        return $this->model->newQuery()->where('code', strtoupper($code))->first();
    }
}
