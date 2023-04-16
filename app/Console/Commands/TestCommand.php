<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TestCommand extends Command
{
    /**
     * @var string
     * @command php artisan test
     */
    protected $signature = 'test';

    /**
     * @return void
     */
    public function handle(): void
    {
        Currency::factory(['code' => 'EUR'])->create();

        try {
            ExchangeRate::factory()->count(100)->create();
        } catch (Exception $e) {
            //
        }

        Artisan::call('migrate:database:currency_codes:history --currency=eur --date_from=2023-04-01');
    }
}
