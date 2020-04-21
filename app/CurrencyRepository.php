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

        if(DatabaseCache::where('key', $this->cacheKey.$base)->exists()){
            return DatabaseCache::where('key', $this->cacheKey.$base)->first()->value;
        }

        $response = Http::get($this->exchange_rate_url."?base=".$base);
        if (!$response->successful()) {
            $response->throw();
        }
        $databaseCache = new DatabaseCache();
        $databaseCache->key = $this->cacheKey.$base;
        $databaseCache->value = $response->json();
        $databaseCache->expiration = $this->exchange_cache_expiry;
        $databaseCache->save();

        return $databaseCache->value;
    }
}