<?php declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyRepository
{
    private $exchange_rate_url;
    private $exchange_cache_expiry;
    private $cacheKey = 'exchange_rate_base_';

    public function __construct(){
        $this->exchange_rate_url = (string) config('exchange.exchange_rate_url');
        $this->exchange_cache_expiry = (int) config('exchange.exchange_cache_expiry');
    }

    public function fetchCurrencyRates($base){
        return Cache::remember($this->cacheKey.$base, $this->exchange_cache_expiry, function () use ($base){
            $response = Http::get($this->exchange_rate_url."?base=".$base);
            if (!$response->successful()) {
                $response->throw();
            }
            return $response->json();
        });
    }
}