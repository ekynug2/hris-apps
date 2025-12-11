<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            // PayrollRun permissions
            ['name' => 'payroll_runs.view_any', 'description' => 'View all payroll runs'],
            ['name' => 'payroll_runs.create', 'description' => 'Create payroll runs'],
            ['name' => 'payroll_runs.update', 'description' => 'Update payroll runs'],
            ['name' => 'payroll_runs.delete', 'description' => 'Delete payroll runs'],

            // PayrollItem permissions
            ['name' => 'payroll_items.view_any', 'description' => 'View all payroll items'],
            ['name' => 'payroll_items.create', 'description' => 'Create payroll items'],
            ['name' => 'payroll_items.update', 'description' => 'Update payroll items'],
            ['name' => 'payroll_items.delete', 'description' => 'Delete payroll items'],

            // Payslip permissions
            ['name' => 'payslips.view_any', 'description' => 'View all payslips'],
            ['name' => 'payslips.create', 'description' => 'Create payslips'],
            ['name' => 'payslips.update', 'description' => 'Update payslips'],
            ['name' => 'payslips.delete', 'description' => 'Delete payslips'],

            // PayrollAdjustment permissions
            ['name' => 'payroll_adjustments.view_any', 'description' => 'View all payroll adjustments'],
            ['name' => 'payroll_adjustments.create', 'description' => 'Create payroll adjustments'],
            ['name' => 'payroll_adjustments.update', 'description' => 'Update payroll adjustments'],
            ['name' => 'payroll_adjustments.delete', 'description' => 'Delete payroll adjustments'],
        ];

        $now = now();
        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission['name'],
                'description' => $permission['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Assign all new permissions to Admin role (role_id = 1)
        $adminRoleId = DB::table('roles')->where('name', 'Admin')->value('id');

        if ($adminRoleId) {
            $permissionIds = DB::table('permissions')
                ->whereIn('name', array_column($permissions, 'name'))
                ->pluck('id');

            foreach ($permissionIds as $permissionId) {
                DB::table('role_permissions')->insertOrIgnore([
                    'role_id' => $adminRoleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissionNames = [
            'payroll_runs.view_any',
            'payroll_runs.create',
            'payroll_runs.update',
            'payroll_runs.delete',
            'payroll_items.view_any',
            'payroll_items.create',
            'payroll_items.update',
            'payroll_items.delete',
            'payslips.view_any',
            'payslips.create',
            'payslips.update',
            'payslips.delete',
            'payroll_adjustments.view_any',
            'payroll_adjustments.create',
            'payroll_adjustments.update',
            'payroll_adjustments.delete',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissionNames)
            ->pluck('id');

        DB::table('role_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('name', $permissionNames)->delete();
    }
};
