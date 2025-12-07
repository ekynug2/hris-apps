<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PayrollTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_payrolls()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/payrolls');
        $response->assertStatus(200);
    }
}
