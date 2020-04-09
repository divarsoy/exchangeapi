<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomepageRenders()
    {
        $response = $this->get('/');
        $response->assertSee('Simple API Test');

        $response->assertStatus(200);
    }
}
