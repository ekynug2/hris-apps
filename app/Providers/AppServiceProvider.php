<?php

namespace App\Providers;

use App\Listeners\UpdateLastLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Observers\GenericAuditObserver;
use App\Models\Attendance;
use App\Models\BioTemplate;
use App\Models\Department;
use App\Models\Device;
use App\Models\DeviceCommand;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeFamily;
use App\Models\EmployeeHistory;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\OrganizationUnit;
use App\Models\Payroll;
use App\Models\PerformanceReview;
use App\Models\Permission;
use App\Models\Position;
use App\Models\Role;
use App\Models\TrainingEnrollment;
use App\Models\TrainingProgram;
use App\Models\User;

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
            Attendance::class,
            BioTemplate::class,
            Department::class,
            Device::class,
            DeviceCommand::class,
            Document::class,
            Employee::class,
            EmployeeFamily::class,
            EmployeeHistory::class,
            LeaveBalance::class,
            LeaveRequest::class,
            LeaveType::class,
            OrganizationUnit::class,
            Payroll::class,
            PerformanceReview::class,
            Permission::class,
            Position::class,
            Role::class,
            TrainingEnrollment::class,
            TrainingProgram::class,
            User::class,
        ];

        foreach ($models as $model) {
            $model::observe(GenericAuditObserver::class);
        }
    }
}
