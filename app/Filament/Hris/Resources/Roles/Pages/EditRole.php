<?php

namespace App\Filament\Hris\Resources\Roles\Pages;

use App\Filament\Hris\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected array $permissionsToSync = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->permissionsToSync = collect($data)
            ->filter(fn($value, $key) => str_starts_with($key, 'permissions_'))
            ->flatten()
            ->toArray();

        return collect($data)
            ->reject(fn($value, $key) => str_starts_with($key, 'permissions_'))
            ->toArray();
    }

    protected function afterSave(): void
    {
        // Get old permissions
        $oldPermissions = $this->record->permissions->pluck('id')->toArray();
        $newPermissions = $this->permissionsToSync;

        // Sync (this updates the DB)
        $this->record->permissions()->sync($this->permissionsToSync);

        // Calculate changes
        $added = array_diff($newPermissions, $oldPermissions);
        $removed = array_diff($oldPermissions, $newPermissions);

        if (!empty($added) || !empty($removed)) {
            // Fetch names for logging
            $addedNames = \App\Models\Permission::whereIn('id', $added)->pluck('name')->toArray();
            $removedNames = \App\Models\Permission::whereIn('id', $removed)->pluck('name')->toArray();

            \App\Models\AuditLog::create([
                'event_time' => now(),
                'event_type' => 'updated',
                'module' => 'ROLES',
                'description' => "Role '{$this->record->name}' permissions updated.",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
                'properties' => [
                    'permissions_added' => $addedNames,
                    'permissions_removed' => $removedNames,
                ],
                'is_from_device' => false,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
