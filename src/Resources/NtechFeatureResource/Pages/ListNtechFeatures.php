<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNtechFeatures extends ListRecords
{
    protected static string $resource = NtechFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
