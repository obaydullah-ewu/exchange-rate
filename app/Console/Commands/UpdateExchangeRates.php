<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ExchangeRateService();
        $service->updateRates();
        $this->info('Exchange rates updated successfully!');
    }
}
