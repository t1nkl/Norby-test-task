<?php

use App\Http\Controllers\Api\V1\CurrencyController;
use App\Http\Controllers\Api\V1\ExchangeRateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    [
        'prefix' => 'currencies',
        'as' => 'currencies.',
    ],
    static function () {
        Route::get('/', [CurrencyController::class, 'getCurrencies'])
            ->name('getCurrencies');

        Route::group(
            [
                'prefix' => '{currency_code}',
                'as' => 'currencyCode.',
            ],
            static function () {
                Route::get('/exchange-rate', [ExchangeRateController::class, 'getByCurrencyCode'])
                    ->name('getByCurrencyCode');

                Route::get('/exchange-rate/{specific_currency_code}', [ExchangeRateController::class, 'getByCurrencyCodeForSpecificCurrency'])
                    ->name('getByCurrencyCodeForSpecificCurrency');
            }
        );
    }
);
