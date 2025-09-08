<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNtechPlans extends ListRecords
{
    protected static string $resource = NtechPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
