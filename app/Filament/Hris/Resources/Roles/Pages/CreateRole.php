<?php

namespace App\Filament\Hris\Resources\Roles\Pages;

use App\Filament\Hris\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected array $permissionsToSync = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->permissionsToSync = collect($data)
            ->filter(fn($value, $key) => str_starts_with($key, 'permissions_'))
            ->flatten()
            ->toArray();

        return collect($data)
            ->reject(fn($value, $key) => str_starts_with($key, 'permissions_'))
            ->toArray();
    }

    protected function afterCreate(): void
    {
        if (!empty($this->permissionsToSync)) {
            $this->record->permissions()->sync($this->permissionsToSync);

            $addedNames = \App\Models\Permission::whereIn('id', $this->permissionsToSync)->pluck('name')->toArray();

            \App\Models\AuditLog::create([
                'event_time' => now(),
                'event_type' => 'created',
                'module' => 'ROLES',
                'description' => "Role '{$this->record->name}' created with permissions.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
                'properties' => [
                    'permissions_initial' => $addedNames,
                ],
                'is_from_device' => false,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
