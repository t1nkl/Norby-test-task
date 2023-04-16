<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes V1
|--------------------------------------------------------------------------
*/

Route::group(
    [
        'prefix' => 'v1',
        'as' => 'api.v1.',
    ],
    static function () {
        require __DIR__ . '/api/v1/api.php';
    }
);
