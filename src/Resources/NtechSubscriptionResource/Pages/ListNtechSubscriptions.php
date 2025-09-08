<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNtechSubscriptions extends ListRecords
{
    protected static string $resource = NtechSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
