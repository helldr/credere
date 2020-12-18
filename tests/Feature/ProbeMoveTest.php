<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProbeMoveTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMoveEndpoint()
    {
        $response = $this->get('api/move');

        $response->assertStatus(405);

        $response = $this->post('api/move');

        $response->assertStatus(200);

        
    }
}
