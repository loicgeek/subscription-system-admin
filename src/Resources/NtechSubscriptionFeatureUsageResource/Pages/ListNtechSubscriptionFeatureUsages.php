<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNtechSubscriptionFeatureUsages extends ListRecords
{
    protected static string $resource = NtechSubscriptionFeatureUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
