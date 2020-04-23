<?php declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Http;

class CurrencyRepository
{
    private $exchange_rate_url;

    public function __construct(){
        $this->exchange_rate_url = (string) config('exchange.exchange_rate_url');
    }

    public function fetchCurrencyRates($base){
        $response = Http::get($this->exchange_rate_url."?base=".$base);
        if (!$response->successful()) {
            $response->throw();
        }
        return $response->json();
    }
}