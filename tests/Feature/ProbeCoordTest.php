<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Probe;

class ProbeCoordTest extends TestCase
{
    /**
     * Testa endpoint Coords
     *
     * @return void
     */
    public function testCoordsEndpoint()
    {
        $response = $this->get('api/coords');

        $response->assertStatus(200);
    }
}
