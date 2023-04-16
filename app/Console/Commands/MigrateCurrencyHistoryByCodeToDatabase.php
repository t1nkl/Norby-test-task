<?php

namespace App\Console\Commands;

use App\ApiProviders\ApiLayerApiProvider;
use App\Models\ExchangeRate;
use App\Repositories\CurrencyRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateCurrencyHistoryByCodeToDatabase extends Command
{
    /**
     * @var string
     * @command php artisan migrate:database:currency_codes:history --currency=eur --date_from=2023-04-01
     */
    protected $signature = 'migrate:database:currency_codes:history
        {--currency=}
        {--date_from=}';

    /**
     * @var string
     */
    protected $description = 'Migrates currency code history from api response to database table.';

    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;

    /**
     * @param  CurrencyRepository  $currencyRepository
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        parent::__construct();
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        if (empty($this->option('currency'))) {
            $this->error('Currency code is required.');

            return;
        }
        if (empty($this->option('date_from'))) {
            $this->error('Date from is required.');

            return;
        }

        $currency = (string)$this->option('currency');
        $currencyCode = $this->currencyRepository->getCurrencyByCode($currency);
        if ($currencyCode === null) {
            $this->error('Currency code not found.');

            return;
        }

        $apiProvider = new ApiLayerApiProvider();
        $currencyHistory = $apiProvider->getCurrencyCodeHistory(
            $currencyCode->code,
            (string)$this->option('date_from')
        );
        if (empty($currencyHistory)) {
            $this->error('No currency code history found.');

            return;
        }

        $this->info('Migrating currency code history from api response to database table...');
        $data = [];
        foreach ($currencyHistory as $date => $currencyCodes) {
            foreach ($currencyCodes as $code => $rate) {
                $currencyCodeData = $this->currencyRepository->getCurrencyByCode($code);
                if ($currencyCodeData === null) {
                    continue;
                }

                $data[] = [
                    'from_currency_id' => $currencyCode->id,
                    'to_currency_id' => $currencyCodeData->id,
                    'rate' => $rate,
                    'created_at' => Carbon::parse($date),
                    'updated_at' => Carbon::parse($date),
                ];
            }
        }
        if (empty($data)) {
            $this->error('No currency code history found.');

            return;
        }

        DB::table(ExchangeRate::getTableName())->insertOrIgnore($data);
        $this->info('Done.');
    }
}
