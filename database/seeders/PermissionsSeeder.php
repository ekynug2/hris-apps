<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'attendances',
            'departments',
            'devices',
            'documents',
            'employee_families',
            'employee_histories',
            'employees',
            'leave_balances',
            'leave_requests',
            'leave_types',
            'organization_units',
            'payrolls',
            'performance_reviews',
            'permissions',
            'positions',
            'roles',
            'training_enrollments',
            'training_programs',
            'users',
        ];

        $actions = [
            'view_any' => 'View (Read)',
            'create' => 'Create (Write)',
            'update' => 'Update (Edit)',
            'delete' => 'Delete (Delete)',
            'soft_delete' => 'Soft Delete (Soft Delete)',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action => $label) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                ], [
                    'description' => "Allow {$action} on {$module}",
                ]);
            }
        }
    }
}
