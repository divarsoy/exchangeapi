<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\ExchangeCache;

class ClearCacheTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClearCache()
    {
        $exchangeCache = new ExchangeCache();
        $exchangeCache->key = "USD-EUR";
        $exchangeCache->from = "USD";
        $exchangeCache->to = "EUR";
        $exchangeCache->multiplier = 0.9202171713;
        $exchangeCache->expiration = 7200;
        $exchangeCache->save();

        $response = $this->get('/api/cache/clear');
        $expected = '{"error":0,"msg":"OK"}';
        $response->assertSeeText($expected, false);
        $response->assertStatus(200);
        $this->assertNull(ExchangeCache::where("USD-EUR")->first());
    }
}
