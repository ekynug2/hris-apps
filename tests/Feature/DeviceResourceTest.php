<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeviceResourceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_can_render_devices_list_page()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/devices');

        $response->assertStatus(200);
    }

    public function test_can_render_create_device_page()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/hris/devices/create');

        $response->assertStatus(200);
    }

    public function test_can_render_edit_device_page()
    {
        $user = \App\Models\User::factory()->create();
        $device = \App\Models\Device::create(['sn' => 'EDIT_TEST_SN']);

        $response = $this->actingAs($user)->get("/hris/devices/{$device->id}/edit");

        $response->assertStatus(200);
    }
}
