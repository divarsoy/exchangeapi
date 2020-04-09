<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FallbackRouteTest extends TestCase
{

    public function testFallbackRouteRendersJson()
    {
        $response = $this->get('/missingRoute');
        $response->assertJson([
            "error" => 1,
            "msg" => "invalid request"
        ]);
        $response->assertStatus(404);
    }
}
