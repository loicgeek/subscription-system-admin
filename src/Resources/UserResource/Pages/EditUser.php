<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\UserResource\Pages;

use NtechServices\SubscriptionSystemAdmin\ResourcesUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
