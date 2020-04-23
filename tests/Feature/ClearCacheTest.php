<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\DatabaseCache;

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
        $currencyPair = [
            "FromCurrency" => "USD",
            "ToCurrency" => "EUR",
            "Multiplier" => 0.9202171713
        ];
        $currencyPairCache = new DatabaseCache();
        $currencyPairCache->key = "USD-EUR";
        $currencyPairCache->value = $currencyPair;
        $currencyPairCache->expiration = 7200;
        $currencyPairCache->save();

        $response = $this->get('/api/cache/clear');
        $expected = '{"error":0,"msg":"OK"}';
        $response->assertSeeText($expected, false);
        $response->assertStatus(200);
        $this->assertNull(DatabaseCache::where("USD-EUR")->first());
    }
}
