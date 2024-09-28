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
            $currency = Currency::whereName($valute->CharCode)->first();
            if (!$currency){
                $currency = new Currency();
            }
            $currency->name = (string)$valute->CharCode;
            $currency->rate = (float)$valute->Value;
            $currency->save();
            CurrencyHistory::create([
                'currency_id' => $currency->id,
                'rate' => $currency->rate,
            ]);
        }
    }
}
