<?php

namespace App\Services;

use App\Repositories\ExchangeRateRepository;

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
}
