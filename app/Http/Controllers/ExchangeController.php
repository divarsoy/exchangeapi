<?php

namespace App\Http\Controllers;

use App\DatabaseCache;
use App\Exchange;
use App\CacheResponse;
use App\InfoResponse;
use Illuminate\Support\Facades\Cache;

class ExchangeController extends Controller
{

    protected function info(){
        $response = new InfoResponse(0, "API written by Dag Ivarsoy");
        return response(json_encode($response->generateResponse()));
    }

    protected function convert($value, $fromCurrency, $toCurrency, Exchange $exchangeModel) {
        $result = $exchangeModel->convert($value, $fromCurrency, $toCurrency);
        return response(json_encode($result->generateResponse()));
    }

    protected function clearCache(){
        DatabaseCache::truncate();
        $response = new CacheResponse(0, "Cache has been cleared");
        return response(json_encode($response->generateResponse()));
    }
}
