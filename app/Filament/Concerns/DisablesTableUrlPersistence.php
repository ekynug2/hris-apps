<?php

namespace App\Filament\Concerns;

/**
 * Trait to disable URL query string persistence for table state.
 * 
 * In Livewire 3, we use the protected queryString() method to control
 * what properties are synced to the URL. By returning an empty array,
 * we prevent any table state from being persisted to the URL.
 */
trait DisablesTableUrlPersistence
{
    /**
     * Override the queryString method to return an empty array.
     * This prevents Livewire from syncing any properties to the URL.
     * 
     * Note: This uses Livewire 3's queryString method hook.
     * The method name pattern is: queryString{TraitName}
     */
    protected function queryStringDisablesTableUrlPersistence(): array
    {
        return [];
    }
}

