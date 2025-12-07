<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CoreHrisResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_organization_units()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/organization-units');
        $response->assertStatus(200);
    }

    public function test_can_view_departments()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/departments');
        $response->assertStatus(200);
    }

    public function test_can_view_positions()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/positions');
        $response->assertStatus(200);
    }

    public function test_can_view_employees()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/employees');
        $response->assertStatus(200);
    }

    public function test_can_create_employee_flow()
    {
        // This tests the page load, not the actual submission which is complex with Livewire.
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/employees/create');
        $response->assertStatus(200);
    }
}
