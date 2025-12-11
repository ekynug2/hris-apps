<?php

namespace App\Filament\Hris\Resources\Roles\Schemas;

use App\Models\Permission;
use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            TextInput::make('name')
                ->label('Nama Peran')
                ->required(),
            Textarea::make('description')
                ->label('Deskripsi')
                ->default(null)
                ->columnSpanFull(),
        ];

        // Dynamically add permission sections
        $permissions = Permission::all();
        $chunks = $permissions->groupBy(fn($p) => explode('.', $p->name)[0]);

        foreach ($chunks as $group => $perms) {
            $components[] = Section::make(Str::headline($group))
                ->schema([
                    CheckboxList::make('permissions_' . $group)
                        ->label('')
                        ->options($perms->pluck('description', 'id'))
                        ->bulkToggleable()
                        ->loadStateFromRelationshipsUsing(fn(CheckboxList $component, ?Role $record) => $record
                            ? $component->state($record->permissions->pluck('id')->toArray())
                            : [])
                ])
                ->collapsed(false)
                ->collapsible();
        }

        return $schema->components($components);
    }
}
