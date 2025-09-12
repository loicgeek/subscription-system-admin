<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;

class ListNtechSubscriptions extends ListRecords
{
    protected static string $resource = NtechSubscriptionResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('subscription-system-admin::subscription.navigation_label');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
