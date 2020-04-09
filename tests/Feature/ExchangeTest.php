<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExchangeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExchangeInfoReturnsCorrectData()
    {
        $response = $this->get('/api/exchange/info');
        $expected = '{"error":0,"msg":"API written by Dag Ivarsoy"}';
        $response->assertSeeText($expected, false);
        $response->assertStatus(200);
    }
}
