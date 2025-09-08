<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
