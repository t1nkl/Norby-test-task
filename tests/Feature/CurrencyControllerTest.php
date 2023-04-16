<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Models\User;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    /**
     * @test
     * @covers \App\Http\Controllers\Api\V1\CurrencyController::getCurrencies
     */
    public function it_can_get_all_currencies(): void
    {
        ExchangeRate::factory()->count(1)->create();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('api.v1.protected.currencies.getCurrencies'));

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'code' => Currency::inRandomOrder()->first()->code,
            ]);
    }
}
