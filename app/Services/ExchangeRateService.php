<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Currency;
use App\Models\CurrencyHistory;

class ExchangeRateService
{
    public function updateRates()
    {
        $response = Http::get('http://www.cbr.ru/scripts/XML_daily.asp');
        $xml = simplexml_load_string($response->body());

        foreach ($xml->Valute as $valute) {
            $currency = Currency::updateOrCreate(
                ['name' => (string)$valute->CharCode],
                ['rate' => (float)$valute->Value]
            );
            CurrencyHistory::create([
                'currency_id' => $currency->id,
                'rate' => $currency->rate,
            ]);
        }
    }
}
