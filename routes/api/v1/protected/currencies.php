<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ExchangeRateController;
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
                Route::get('/exchange-rate', [ExchangeRateController::class, 'getExchangeRateByCurrencyCode'])
                    ->name('getExchangeRateByCurrencyCode');
            }
        );
    }
);
