<?php

namespace App\Services;

use App\Repositories\CurrencyRepository;

class CurrencyService
{
    public function __construct(public CurrencyRepository $repository)
    {
    }

    /**
     * @return CurrencyRepository
     */
    public function getRepository(): CurrencyRepository
    {
        return $this->repository;
    }
}
