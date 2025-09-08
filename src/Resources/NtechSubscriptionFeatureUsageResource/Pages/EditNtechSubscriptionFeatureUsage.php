<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource;

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
