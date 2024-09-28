<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Currency;
use App\Models\CurrencyHistory;

class ExchangeRateService
{
    public function updateRates()
    {
        // Fetch data from an external API, e.g., Central Bank of Russia
        $response = Http::get('http://www.cbr.ru/scripts/XML_daily.asp');

        // Process and parse the response (assuming it’s XML)
        $xml = simplexml_load_string($response->body());

        foreach ($xml->Valute as $valute) {
            // Update or create the currency in the database
            $currency = Currency::updateOrCreate(
                ['name' => (string)$valute->CharCode],
                ['rate' => (float)$valute->Value]
            );

            // Log the change in the history
            CurrencyHistory::create([
                'currency_id' => $currency->id,
                'rate' => $currency->rate,
            ]);
        }
    }
}
