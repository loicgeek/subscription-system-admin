<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;


use Filament\Resources\Pages\ListRecords;   
use Illuminate\Contracts\Support\Htmlable;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;

class ListNtechPlans extends ListRecords
{
    protected static string $resource = NtechPlanResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('subscription-system-admin::plan.navigation_label');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
