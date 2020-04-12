<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class ClearCacheTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClearCache()
    {
        $currencyPair = [
            "FromCurrency" => "USD",
            "ToCurrency" => "EUR",
            "Multiplier" => 0.9202171713
        ];
        Cache::put("USD-EUR", $currencyPair, 7200 );
        $apiResponse = [
            "rates" => [
                "CAD" => "1.5265",
                "HKD" => "8.4259",
                "ISK" => "155.9",
                "PHP" => "54.939",
                "DKK" => "7.4657",
                "HUF" => "354.76",
                "CZK" => "26.909",
                "AUD" => "1.7444",
                "RON" => "4.833",
                "SEK" => "10.9455",
                "IDR" => "17243.21",
                "INR" => "82.9275",
                "BRL" => "5.5956",
                "RUB" => "80.69",
                "HRK" => "7.6175",
                "JPY" => "118.33",
                "THB" => "35.665",
                "CHF" => "1.0558",
                "SGD" => "1.5479",
                "PLN" => "4.5586",
                "BGN" => "1.9558",
                "TRY" => "7.3233",
                "CNY" => "7.6709",
                "NOK" => "11.2143",
                "NZD" => "1.8128",
                "ZAR" => "19.6383",
                "USD" => "1.0867",
                "MXN" => "26.0321",
                "ILS" => "3.8919",
                "GBP" => "0.87565",
                "KRW" => "1322.49",
                "MYR" => "4.7136"
            ],
            "base" => "EUR",
            "date" => "2020-04-09"
        ];
        Cache::put("exchange_rate_base_EUR", $apiResponse,7200);

        $response = $this->get('/api/cache/clear');
        $expected = '{"error":0,"msg":"Cache has been cleared"}';
        $response->assertSeeText($expected, false);
        $response->assertStatus(200);
        $this->assertNull(Cache::get("exchange_rate_base_EUR"));
        $this->assertNull(Cache::get("USD-EUR"));
    }
}