<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\ProjectResource\Pages;

use NtechServices\SubscriptionSystemAdmin\ResourcesProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
