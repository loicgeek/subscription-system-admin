<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\UserResource\Pages;

use NtechServices\SubscriptionSystemAdmin\ResourcesUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
