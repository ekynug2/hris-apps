<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LeaveManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_leave_requests()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/leave-requests');
        $response->assertStatus(200);
    }

    public function test_can_view_leave_types()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/leave-types');
        $response->assertStatus(200);
    }

    public function test_can_view_leave_balances()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/leave-balances');
        $response->assertStatus(200);
    }
}
