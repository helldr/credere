<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProbeInitTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testInitEndpoint()
    {
        $response = $this->get('api/init');

        $response->assertStatus(405);

        $response = $this->post('api/init');

        $response->assertStatus(200);

        
    }
}
