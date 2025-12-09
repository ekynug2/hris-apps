<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class BaseHrisPolicy
{
    use HandlesAuthorization;

    protected ?string $modelName = null;

    /**
     * Get the permission prefix based on the model name.
     * e.g., 'App\Models\Document' -> 'documents'
     */
    protected function getPermissionPrefix(): string
    {
        if ($this->modelName) {
            return $this->modelName;
        }

        // Infer from class name: App\Policies\DocumentPolicy -> Document -> documents
        $class = class_basename(static::class);
        $model = Str::replaceLast('Policy', '', $class);
        return Str::snake(Str::plural($model));
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission($this->getPermissionPrefix() . '.view_any');
    }

    public function view(User $user, $model): bool
    {
        return $user->hasPermission($this->getPermissionPrefix() . '.view_any');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission($this->getPermissionPrefix() . '.create');
    }

    public function update(User $user, $model): bool
    {
        return $user->hasPermission($this->getPermissionPrefix() . '.update');
    }

    public function delete(User $user, $model): bool
    {
        return $user->hasPermission($this->getPermissionPrefix() . '.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission($this->getPermissionPrefix() . '.delete');
    }
}
