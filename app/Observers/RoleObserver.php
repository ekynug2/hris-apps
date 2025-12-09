<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleObserver
{
    public function created(Role $role): void
    {
        $this->log($role, 'created', "Role '{$role->name}' was created.");
    }

    public function updated(Role $role): void
    {
        // Check if only timestamps changed
        if ($role->wasChanged() && !$role->isDirty('updated_at')) {
            $this->log($role, 'updated', "Role '{$role->name}' was updated.");
        } elseif ($role->wasChanged(['name', 'description'])) {
            $this->log($role, 'updated', "Role '{$role->name}' was updated.");
        }
    }

    public function deleted(Role $role): void
    {
        $this->log($role, 'deleted', "Role '{$role->name}' was deleted.");
    }

    protected function log(Role $role, string $type, string $description): void
    {
        AuditLog::create([
            'event_time' => now(),
            'event_type' => $type,
            'module' => 'ROLES',
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => Auth::id(),
            'properties' => $role->getChanges(),
            'is_from_device' => false,
        ]);
    }
}
