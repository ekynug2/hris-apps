<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationUnit;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Device;
use Carbon\Carbon;

class HrisDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Root Organization Unit (Head Office)
        // Check if ID 1 exists, if not create it explicitly with ID 1 to satisfy your failed query if hardcoded
        $rootOrg = OrganizationUnit::firstOrCreate(
            ['name' => 'Head Office'],
            ['type' => 'Head']
        );

        // 2. Create Child Organization Unit (BackOffice)
        $backOffice = OrganizationUnit::create([
            'name' => 'BackOffice',
            'type' => 'Division',
            'parent_id' => $rootOrg->id
        ]);

        // 3. Create Departments
        $itDept = Department::create([
            'name' => 'Information Technology',
            'organization_unit_id' => $backOffice->id
        ]);

        $hrDept = Department::create([
            'name' => 'Human Resources',
            'organization_unit_id' => $backOffice->id
        ]);

        // 4. Create Positions
        $itManagerPos = Position::create([
            'title' => 'IT Manager',
            'base_salary' => 15000000,
            'level' => 'Senior',
            'department_id' => $itDept->id
        ]);

        $hrStaffPos = Position::create([
            'title' => 'HR Staff',
            'base_salary' => 5000000,
            'level' => 'Junior',
            'department_id' => $hrDept->id
        ]);

        // 5. Create Employees
        Employee::create([
            'nik' => 'EMP001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@hris.com',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'hire_date' => Carbon::parse('2023-01-01'),
            'position_id' => $itManagerPos->id,
        ]);

        // 6. Create Devices
        // 6. Create Devices
        Device::create([
            'sn' => 'SN12345678',
            'alias' => 'Main Gate Attendance',
            'ip_address' => '192.168.1.201',
            'department_id' => $itDept->id,
            'state' => 1,
            'last_activity' => now(),
        ]);
    }
}
