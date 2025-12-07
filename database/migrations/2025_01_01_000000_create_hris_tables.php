<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Organization Units
        Schema::create('organization_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50)->comment('Area/Region/Branch');
            $table->foreignId('parent_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->json('meta')->nullable(); // For Filament flexible fields
            $table->timestamps();
        });

        // 2. Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('organization_unit_id')->constrained('organization_units')->cascadeOnDelete();
            // head_id FK will be added later due to circular dependency with employees
            $table->unsignedBigInteger('head_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        // 3. Positions
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('base_salary', 15, 2);
            $table->string('level', 50)->comment('Junior/Middle/Senior');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->json('meta')->nullable(); // For extra position details
            $table->timestamps();
        });

        // 4. Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 50)->unique()->comment('Nomor Induk Karyawan');
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->date('hire_date');
            $table->date('resignation_date')->nullable()->comment('Tanggal pengajuan resign');
            $table->date('termination_date')->nullable()->comment('Last working day');
            // 'employment_status' - active, probation, etc.
            $table->enum('employment_status', ['active', 'on_probation', 'on_notice', 'resigned', 'terminated'])->default('on_probation');
            $table->boolean('is_blacklisted')->default(false)->comment('Pernah melanggar berat?');

            // Contact info - often flexible in HR
            $table->string('email')->nullable(); // Personal/Work email for contact (not login)
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();

            $table->foreignId('position_id')->constrained('positions');
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();

            $table->json('custom_fields')->nullable(); // For Filament Builder or flexible attributes
            $table->timestamps();
        });

        // Add circular FK for departments -> head_id (references employees.id)
        Schema::table('departments', function (Blueprint $table) {
            $table->foreign('head_id', 'fk_department_head')->references('id')->on('employees')->nullOnDelete();
        });

        // 5. Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('Admin, HR, Staff');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 6. Users (Standard Laravel Structure for Filament)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Required by Filament
            $table->string('email')->unique(); // Replaced 'username' with 'email' for standard auth
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Standard password column
            $table->rememberToken();

            // HRIS Specific
            $table->boolean('is_active')->default(true);
            $table->dateTime('last_login')->nullable();

            // Link to Employee & Role
            $table->foreignId('employee_id')->nullable()->unique()->constrained('employees')->nullOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();

            $table->timestamps();
        });

        // 7. Permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('e.g., attendance.manage');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 8. Role Permissions
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // 9. Leave Types
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('default_days');
            $table->boolean('requires_document')->default(false);
            $table->timestamps();
        });

        // 10. Leave Requests
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->float('total_days');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled'])->default('draft');
            $table->text('reason')->nullable();
            $table->text('rejection_note')->nullable();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->foreignId('backup_approver_id')->nullable()->constrained('employees');
            $table->timestamps();
        });

        // 11. Leave Balances
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('balance');
            $table->integer('initial_balance');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->timestamps();
        });

        // 12. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent']);
            $table->text('note')->nullable();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            // GPS Location if needed
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->timestamps();
        });

        // 13. Payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowance_transport', 15, 2);
            $table->decimal('allowance_meal', 15, 2);
            $table->decimal('bpjs_kesehatan', 15, 2);
            $table->decimal('bpjs_ketenagakerjaan', 15, 2);
            $table->decimal('pph21', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->enum('status', ['calculated', 'approved', 'paid', 'rejected']);
            $table->text('rejection_note')->nullable();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->json('details')->nullable(); // Flexible components
            $table->timestamps();
        });

        // 14. Performance Reviews
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('review_period', 50);
            $table->float('rating');
            $table->text('comments')->nullable();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('employees');
            $table->json('kpi_scores')->nullable(); // Flexible scoring
            $table->timestamps();
        });

        // 15. Training Programs
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        // 16. Training Enrollments
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['registered', 'completed', 'failed']);
            $table->text('certificate_url')->nullable();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('training_programs')->cascadeOnDelete();
            $table->timestamps();
        });

        // 17. Employee Families
        Schema::create('employee_families', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('relation', ['spouse', 'child', 'parent']);
            $table->date('date_of_birth');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamps();
        });

        // 18. Documents
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('file_path');
            $table->dateTime('uploaded_at');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });

        // 19. Employee Histories
        Schema::create('employee_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('effective_date');
            $table->enum('change_type', ['position', 'department', 'salary', 'status']);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('changed_by')->constrained('users');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamps();
        });

        // 20. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->dateTime('event_time');
            $table->string('event_type');
            $table->string('module');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('properties')->nullable();
            $table->timestamps();
        });

        // 21. Devices
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('sn', 50)->unique();
            $table->string('ip_address', 50)->nullable();
            $table->dateTime('last_activity')->nullable();
            $table->text('options')->nullable();
            $table->string('push_version', 50)->nullable();
            $table->string('dev_language', 50)->nullable();
            $table->timestamps();
        });

        // 22. Device Commands
        Schema::create('device_commands', function (Blueprint $table) {
            $table->id();
            $table->string('device_sn', 50);
            $table->foreign('device_sn')->references('sn')->on('devices')->cascadeOnDelete();
            $table->text('content');
            $table->dateTime('commit_time')->useCurrent();
            $table->dateTime('trans_time')->nullable();
            $table->string('return_value')->nullable();
            $table->timestamps();
        });

        // Standard Laravel tables
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('device_commands');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('employee_histories');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('employee_families');
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('training_programs');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');

        // Drop circular foreign key
        if (Schema::hasTable('departments')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropForeign('fk_department_head');
            });
        }

        Schema::dropIfExists('employees');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('organization_units');
    }
};
