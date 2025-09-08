<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\SubscriptionPaymentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionPayment extends EditRecord
{
    protected static string $resource = SubscriptionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
