<?php

namespace App\Console\Commands;

use App\ApiProviders\ApiLayerApiProvider;
use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateCurrencyCodesToDatabase extends Command
{
    /**
     * @var string
     * @command php artisan migrate:database:currency_codes
     */
    protected $signature = 'migrate:database:currency_codes';

    /**
     * @var string
     */
    protected $description = 'Migrates currency codes from api response to database table.';

    /**
     * @return void
     */
    public function handle(): void
    {
        $apiProvider = new ApiLayerApiProvider();
        $currencyCodes = $apiProvider->getCurrencyCodes();

        if (empty($currencyCodes)) {
            $this->error('No currency codes found.');

            return;
        }

        $this->info('Migrating currency codes from api response to database table...');
        foreach ($currencyCodes as $code => $name) {
            DB::table(Currency::getTableName())->insertOrIgnore([
                'code' => $code,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('Done.');
    }
}
