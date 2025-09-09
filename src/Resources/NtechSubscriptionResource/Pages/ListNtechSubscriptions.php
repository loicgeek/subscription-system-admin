<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;

class ListNtechSubscriptions extends ListRecords
{
    protected static string $resource = NtechSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
