<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource;

class EditNtechFeature extends EditRecord
{
    protected static string $resource = NtechFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
