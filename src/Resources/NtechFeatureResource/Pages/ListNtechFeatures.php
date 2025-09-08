<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource;

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
