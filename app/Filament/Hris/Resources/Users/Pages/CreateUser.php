<?php

namespace App\Filament\Hris\Resources\Users\Pages;

use App\Filament\Hris\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
