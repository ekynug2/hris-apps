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
        // 1. Payroll Runs - Main payroll period/batch
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Payroll - Desember 2025"
            $table->date('period_start');
            $table->date('period_end');
            $table->date('pay_date');
            $table->enum('status', ['draft', 'in_progress', 'finalized', 'cancelled'])->default('draft');

            // Scope filters (optional)
            $table->foreignId('organization_unit_id')->nullable()->constrained('organization_units')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

            // Summary totals (calculated)
            $table->integer('total_employees')->default(0);
            $table->decimal('total_gross', 18, 2)->default(0);
            $table->decimal('total_deductions', 18, 2)->default(0);
            $table->decimal('total_net_pay', 18, 2)->default(0);

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('finalized_at')->nullable();

            $table->json('settings')->nullable(); // For payroll template settings
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Payroll Items - Individual employee payroll in a run
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            // Earnings
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('allowance_position', 15, 2)->default(0);
            $table->decimal('allowance_transport', 15, 2)->default(0);
            $table->decimal('allowance_meal', 15, 2)->default(0);
            $table->decimal('overtime_pay', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('other_earnings', 15, 2)->default(0);

            // Deductions
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('pph21', 15, 2)->default(0);
            $table->decimal('loan_deduction', 15, 2)->default(0);
            $table->decimal('late_penalty', 15, 2)->default(0);
            $table->decimal('absence_deduction', 15, 2)->default(0);
            $table->decimal('other_deductions', 15, 2)->default(0);

            // Totals
            $table->decimal('gross_pay', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_pay', 15, 2)->default(0);

            // Attendance data for this period
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('late_count')->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);

            // Status & notes
            $table->boolean('is_included')->default(true);
            $table->text('adjustment_reason')->nullable();

            $table->json('components')->nullable(); // Detailed breakdown
            $table->timestamps();

            // Unique constraint: one employee per payroll run
            $table->unique(['payroll_run_id', 'employee_id']);
        });

        // 3. Payslips - Generated payslips for employees
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_item_id')->constrained('payroll_items')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            $table->string('slip_number')->unique(); // e.g., SLIP-2025-12-0001
            $table->date('period_start');
            $table->date('period_end');
            $table->date('pay_date');

            $table->decimal('gross_pay', 15, 2);
            $table->decimal('total_deductions', 15, 2);
            $table->decimal('net_pay', 15, 2);

            $table->enum('status', ['generated', 'published', 'viewed', 'downloaded'])->default('generated');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('first_viewed_at')->nullable();

            $table->string('pdf_path')->nullable();
            $table->json('data')->nullable(); // Snapshot of payroll data

            $table->timestamps();
        });

        // 4. Payroll Adjustments Log
        Schema::create('payroll_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_item_id')->constrained('payroll_items')->cascadeOnDelete();
            $table->foreignId('adjusted_by')->constrained('users')->cascadeOnDelete();

            $table->string('component'); // Which component was adjusted
            $table->decimal('old_value', 15, 2);
            $table->decimal('new_value', 15, 2);
            $table->text('reason');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_adjustments');
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
    }
};
