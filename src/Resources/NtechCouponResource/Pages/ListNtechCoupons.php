<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource;

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
