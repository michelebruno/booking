<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DealTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/api/v1/deals');

        $response->assertStatus(200);

    }

    public function testMustReturnUnauthorized()
    {
        $this->postJson('/api/v1/deals')->assertStatus(401);
        $this->putJson("/api/v1/deals/D-1")->assertStatus(401);
        $this->deleteJson("/api/v1/deals/D-1")->assertStatus(401);

    }


}
