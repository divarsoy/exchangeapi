<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Responses\ErrorResponse;
use App\CurrencyRepository;
use App\Service;
use App\Exchange;
use App\ExchangeCache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExchangeTest extends TestCase
{
    use RefreshDatabase;

    private $currencyRepository;

    public function testConvertWithNotSupportedFromCurrency()
    {
        $expected = [
            "error" => 1,
            'msg' => "currency code NOK not supported"
        ];
        $currencyRepository = $this->createMock(CurrencyRepository::class);

        $exchange = new Exchange($currencyRepository);
        $actual = $exchange->convert(100, 'NOK', 'USD');
        $this->assertInstanceOf(ErrorResponse::class, $actual);
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testConvertWithNotSupportedToCurrency()
    {
        $expected = [
            "error" => 1,
            'msg' => "currency code NOK not supported"
        ];

        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $exchange = new Exchange($currencyRepository);
        $actual = $exchange->convert(100, 'USD', 'NOK');
        $this->assertInstanceOf(ErrorResponse::class, $actual);
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testConvertWithInvalidValue()
    {
        $expected = [
            "error" => 1,
            'msg' => "value invalid must be numeric"
        ];
        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $exchange = new Exchange($currencyRepository);

        $actual = $exchange->convert('invalid', 'USD', 'EUR');
        $this->assertInstanceOf(ErrorResponse::class, $actual);
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testConvertingFromAndToTheSameCurrency(){
        $expected = [
            "error" => 0,
            "amount" => "100.00",
            "fromCache" => 0
        ];
        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $exchange = new Exchange($currencyRepository);

        $actual = $exchange->convert(100, 'EUR', 'EUR');
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testFailedToFetchFromAPI()
    {
        $currencyRepository = $this->mock(CurrencyRepository::class, function ($mock) {
            $mock->shouldReceive('fetchCurrencyRates')->with('USD')->once()->andThrow(\Exception::class);
        });

        $exchange = new Exchange($currencyRepository);
        $expected = [
            "error" => 1,
            'msg' => "Could not fetch currency rates, please try again later"
        ];
        $actual = $exchange->convert('100', 'USD', 'EUR');
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testConvertWithThousandSeparatorValue()
    {
        $expected = [
            "error" => 1,
            'msg' => "value 1,000.00 must be numeric"
        ];
        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $exchange = new Exchange($currencyRepository);
        $actual = $exchange->convert('1,000.00', 'USD', 'EUR');
        $this->assertInstanceOf(ErrorResponse::class, $actual);
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testGettingMultiplierFromCache()
    {
        $exchangeCache = new ExchangeCache();
        $exchangeCache->key = "USD-EUR";
        $exchangeCache->from = "USD";
        $exchangeCache->to = "EUR";
        $exchangeCache->multiplier = 0.9202171713;
        $exchangeCache->expiration = 7200;
        $exchangeCache->save();

        $currencyRepository = $this->createMock(CurrencyRepository::class);
        $exchange = new Exchange($currencyRepository);
        $expected = [
            "error" => 0,
            "amount" => "92.02",
            "fromCache" => 1
        ];
        $actual = $exchange->convert('100', 'USD', 'EUR');
        $this->assertEquals($expected, $actual->generateResponse());
    }


    public function testCalculating100EURtoUSD()
    {
        $expected = [
            "error" => 0,
            "amount" => "108.67",
            "fromCache" => 0
        ];
        $currencyRepository = $this->mock(CurrencyRepository::class, function ($mock){
            $mock->shouldReceive('fetchCurrencyRates')->with('EUR')->once()->andReturn([
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
            ]);
        });
        $exchange = new Exchange($currencyRepository);

        $actual = $exchange->convert(100, 'EUR', 'USD');
        $this->assertEquals($expected, $actual->generateResponse());
    }

    public function testCalculating100USDtoEUR()
    {
        $expected = [
            "error" => 0,
            "amount" => "92.02",
            "fromCache" => 0
        ];
        $currencyRepository = $this->mock(CurrencyRepository::class, function ($mock){
            $mock->shouldReceive('fetchCurrencyRates')->with('USD')->once()->andReturn([
                "rates" => [
                    "CAD" => "1.4047115119",
                    "HKD" => "7.7536578633",
                    "ISK" => "143.4618569983",
                    "PHP" => "50.5558111714",
                    "DKK" => "6.8700653354",
                    "HUF" => "326.4562436735",
                    "CZK" => "24.7621238612",
                    "GBP" => "0.805788166",
                    "RON" => "4.4474095887",
                    "SEK" => "10.0722370479",
                    "IDR" => "15867.4979295114",
                    "INR" => "76.311309469",
                    "BRL" => "5.1491672035",
                    "RUB" => "74.2523235484",
                    "HRK" => "7.009754302",
                    "JPY" => "108.8892978743",
                    "THB" => "32.8195454127",
                    "CHF" => "0.9715652894",
                    "EUR" => "0.9202171713",
                    "MYR" => "4.3375356584",
                    "BGN" => "1.7997607435",
                    "TRY" => "6.7390264102",
                    "CNY" => "7.058893899",
                    "NOK" => "10.3195914236",
                    "NZD" => "1.668169688",
                    "ZAR" => "18.0715008742",
                    "USD" => "1",
                    "MXN" => "23.9551854238",
                    "SGD" => "1.4244041594",
                    "AUD" => "1.6052268335",
                    "ILS" => "3.5813932088",
                    "KRW" => "1216.9780068096",
                    "PLN" => "4.1949019969"
                ],
                    "base" => "USD",
                    "date" => "2020-04-09"
            ]);
        });
        $exchange = new Exchange($currencyRepository);

        $actual = $exchange->convert(100, 'USD', 'EUR');
        $this->assertEquals($expected, $actual->generateResponse());
    }





}
