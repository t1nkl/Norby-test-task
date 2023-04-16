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
     * @covers \App\Http\Controllers\Api\ExchangeRateController::getExchangeRateByCurrencyCode
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
                    'api.v1.protected.currencies.currencyCode.getExchangeRateByCurrencyCode',
                    ['currency_code' => $currency->code]
                )
            )
            ->assertOk()
            ->assertJsonFragment([
                'code' => $randomCurrency->code,
            ]);
    }
}
