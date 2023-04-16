<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\User;
use Tests\TestCase;

class ExchangeRateControllerTest extends TestCase
{
    /**
     * @test
     * @covers \App\Http\Controllers\Api\V1\ExchangeRateController::getByCurrencyCode
     */
    public function it_can_get_exchange_rate_by_currency_code(): void
    {
        $currency = Currency::factory()->count(1)->create()->first();
        ExchangeRate::factory(
            [
                'from_currency_id' => $currency->id,
            ]
        )->count(10)->create();

        /** @var Currency $randomCurrency */
        $randomCurrency = Currency::where('code', '!=', $currency->code)->inRandomOrder()->first();

        $this->actingAs(User::factory()->create())
            ->getJson(
                route(
                    'api.v1.protected.currencies.currencyCode.getByCurrencyCode',
                    ['currency_code' => $currency->code]
                )
            )
            ->assertOk()
            ->assertJsonFragment([
                'code' => $randomCurrency->code,
            ]);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\Api\V1\ExchangeRateController::getByCurrencyCode
     */
    public function it_can_get_exchange_rate_by_currency_code_by_date_from_and_date_to(): void
    {
        $currency = Currency::factory()->count(1)->create()->first();
        for ($i = 1; $i <= 10; $i++) {
            ExchangeRate::factory(
                [
                    'from_currency_id' => $currency->id,
                    'created_at' => now()->subDays($i),
                ]
            )->create();
        }

        $this->actingAs(User::factory()->create())
            ->getJson(
                route(
                    'api.v1.protected.currencies.currencyCode.getByCurrencyCode',
                    [
                        'currency_code' => $currency->code,
                        'date_from' => now()->subDays(5)->format('Y-m-d'),
                        'date_to' => now()->subDays(2)->format('Y-m-d'),
                    ]
                )
            )
            ->assertOk()
            ->assertJsonCount(3);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\Api\V1\ExchangeRateController::getByCurrencyCode
     */
    public function it_can_get_exchange_rate_by_currency_code_by_date_from(): void
    {
        $currency = Currency::factory()->count(1)->create()->first();
        for ($i = 1; $i <= 10; $i++) {
            ExchangeRate::factory(
                [
                    'from_currency_id' => $currency->id,
                    'created_at' => now()->subDays($i),
                ]
            )->create();
        }

        $randomInt = random_int(2, 10);

        $this->actingAs(User::factory()->create())
            ->getJson(
                route(
                    'api.v1.protected.currencies.currencyCode.getByCurrencyCode',
                    [
                        'currency_code' => $currency->code,
                        'date_from' => now()->subDays($randomInt)->format('Y-m-d')
                    ]
                )
            )
            ->assertOk()
            ->assertJsonCount($randomInt);
    }

    /**
     * @test
     * @covers \App\Http\Controllers\Api\V1\ExchangeRateController::getByCurrencyCodeForSpecificCurrency
     */
    public function it_can_get_exchange_rate_by_currency_code_for_specific_currency(): void
    {
        $currency = Currency::factory()->count(1)->create()->first();
        ExchangeRate::factory(
            [
                'from_currency_id' => $currency->id,
            ]
        )->count(10)->create();

        /** @var Currency $randomCurrency */
        $randomCurrency = Currency::where('code', '!=', $currency->code)->inRandomOrder()->first();

        $this->actingAs(User::factory()->create())
            ->getJson(
                route(
                    'api.v1.protected.currencies.currencyCode.getByCurrencyCodeForSpecificCurrency',
                    [
                        'currency_code' => $currency->code,
                        'specific_currency_code' => $randomCurrency->code,
                    ]
                )
            )
            ->assertOk();
    }
}
