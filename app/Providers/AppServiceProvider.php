<?php

namespace App\Providers;

use App\Listeners\UpdateLastLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            Login::class,
            UpdateLastLogin::class,
        );

        // List of models to observe
        $models = [
            \App\Models\Attendance::class,
            \App\Models\BioTemplate::class,
            \App\Models\Department::class,
            \App\Models\Device::class,
            \App\Models\DeviceCommand::class,
            \App\Models\Document::class,
            \App\Models\Employee::class,
            \App\Models\EmployeeFamily::class,
            \App\Models\EmployeeHistory::class,
            \App\Models\LeaveBalance::class,
            \App\Models\LeaveRequest::class,
            \App\Models\LeaveType::class,
            \App\Models\OrganizationUnit::class,
            \App\Models\Payroll::class,
            \App\Models\PerformanceReview::class,
            \App\Models\Permission::class,
            \App\Models\Position::class,
            \App\Models\Role::class,
            \App\Models\TrainingEnrollment::class,
            \App\Models\TrainingProgram::class,
            \App\Models\User::class,
        ];

        foreach ($models as $model) {
            $model::observe(\App\Observers\GenericAuditObserver::class);
        }
    }
}
