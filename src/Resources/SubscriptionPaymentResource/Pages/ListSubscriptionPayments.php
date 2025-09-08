<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\SubscriptionPaymentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptionPayments extends ListRecords
{
    protected static string $resource = SubscriptionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
