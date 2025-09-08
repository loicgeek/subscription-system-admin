<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;

class EditNtechSubscription extends EditRecord
{
    protected static string $resource = NtechSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
