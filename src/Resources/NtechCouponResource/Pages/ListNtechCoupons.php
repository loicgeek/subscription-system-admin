<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNtechCoupons extends ListRecords
{
    protected static string $resource = NtechCouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
