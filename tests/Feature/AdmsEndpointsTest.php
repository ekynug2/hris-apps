<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdmsEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_device_can_register()
    {
        $sn = 'TEST_DEVICE_001';
        $response = $this->get("/iclock/registry?SN={$sn}&pushver=3.0&language=english");

        $response->assertStatus(200);
        $response->assertSee('Registry=1');
        $response->assertSee('RegistryCode=1');

        $this->assertDatabaseHas('devices', [
            'sn' => $sn,
            'push_version' => '3.0',
            'dev_language' => 'english',
        ]);
    }

    public function test_device_cdata_heartbeat()
    {
        // First create device
        $sn = 'TEST_DEVICE_002';
        \App\Models\Device::create(['sn' => $sn]);

        $response = $this->get("/iclock/cdata?SN={$sn}&table=OPERLOG"); // Just a keepalive/oplog

        $response->assertStatus(200);
        $response->assertSee('OK');
    }

    public function test_device_pushes_attendance_log()
    {
        $sn = 'TEST_DEVICE_ATT';
        \App\Models\Device::create(['sn' => $sn]);

        // Create Dependencies first
        $org = \App\Models\OrganizationUnit::create(['name' => 'HQ', 'type' => 'Branch']);
        $dept = \App\Models\Department::create(['name' => 'IT', 'organization_unit_id' => $org->id]);
        $pos = \App\Models\Position::create([
            'title' => 'Dev',
            'base_salary' => 5000000,
            'level' => 'Junior',
            'department_id' => $dept->id
        ]);

        // Create Employee with position_id
        $employee = \App\Models\Employee::create([
            'nik' => '12345',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'hire_date' => now(),
            'position_id' => $pos->id,
        ]);

        // Prepare body based on ADMS format: PIN \t Time \t Status \t ...
        // 12345 2025-01-01 08:00:00 0 1 0 0
        $body = "12345\t2025-01-01 08:00:00\t0\t1\t0\t0\t0";

        $response = $this->call('POST', "/iclock/cdata?SN={$sn}&table=ATTLOG", [], [], [], [], $body);

        $response->assertStatus(200);
        $response->assertSee('OK:1');

        $this->assertDatabaseHas('attendances', [
            'employee_id' => $employee->id,
            'date' => '2025-01-01',
            'clock_in' => '08:00:00',
        ]);
    }

    public function test_get_request_for_commands()
    {
        $sn = 'TEST_DEVICE_CMD';
        \App\Models\Device::create(['sn' => $sn]);

        // No commands initially
        $response = $this->get("/iclock/getrequest?SN={$sn}");
        $response->assertSee('OK');

        // Add a command
        \App\Models\DeviceCommand::create([
            'device_sn' => $sn,
            'content' => 'REBOOT',
        ]);

        $response = $this->get("/iclock/getrequest?SN={$sn}");
        // Should see C:ID:REBOOT
        $response->assertSee("C:");
        $response->assertSee("REBOOT");
    }
}
