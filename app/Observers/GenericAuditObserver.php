<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GenericAuditObserver
{
    public function created(Model $model): void
    {
        $this->log($model, 'created', $this->getDescription($model, 'created'));
    }

    public function updated(Model $model): void
    {
        // Skip if only timestamps changed
        if ($model->wasChanged() && count($model->getChanges()) === 1 && $model->wasChanged('updated_at')) {
            return;
        }

        $this->log($model, 'updated', $this->getDescription($model, 'updated'));
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted', $this->getDescription($model, 'deleted'));
    }

    public function restored(Model $model): void
    {
        $this->log($model, 'restored', $this->getDescription($model, 'restored'));
    }

    public function forceDeleted(Model $model): void
    {
        $this->log($model, 'force_deleted', $this->getDescription($model, 'force deleted'));
    }

    protected function getDescription(Model $model, string $action): string
    {
        $modelName = class_basename($model);
        // Try to identify a recognizable name/title
        $identifier = $model->name ?? $model->title ?? $model->code ?? $model->sn ?? $model->nik ?? $model->id;

        return "{$modelName} '{$identifier}' was {$action}.";
    }

    protected function log(Model $model, string $type, string $description): void
    {
        // Avoid logging AuditLog itself (though we won't observe it)
        if ($model instanceof AuditLog) {
            return;
        }

        $moduleName = Str::upper(Str::snake(Str::plural(class_basename($model))));

        // Capture changed attributes for updates
        $properties = [];
        if ($type === 'updated') {
            foreach ($model->getChanges() as $key => $value) {
                if ($key === 'updated_at')
                    continue;
                $properties[$key] = [
                    'old' => $model->getOriginal($key),
                    'new' => $value,
                ];
            }
        } elseif ($type === 'created') {
            $properties = $model->toArray();
        }

        AuditLog::create([
            'event_time' => now(),
            'event_type' => $type,
            'module' => $moduleName,
            'description' => $description,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
            'user_id' => Auth::id(), // Null if system/device action
            'properties' => $properties,
            'is_from_device' => false,
        ]);
    }
}
