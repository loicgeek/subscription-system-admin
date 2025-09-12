<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource;

class ListNtechFeatures extends ListRecords
{
    protected static string $resource = NtechFeatureResource::class;


    public function getTitle(): string | Htmlable
    {
        return __('subscription-system-admin::feature.navigation_label');
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
