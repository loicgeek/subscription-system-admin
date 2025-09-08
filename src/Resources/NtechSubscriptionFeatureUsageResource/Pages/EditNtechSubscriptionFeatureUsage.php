<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNtechSubscriptionFeatureUsage extends EditRecord
{
    protected static string $resource = NtechSubscriptionFeatureUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
