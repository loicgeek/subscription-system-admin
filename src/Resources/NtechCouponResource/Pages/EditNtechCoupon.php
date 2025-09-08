<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource\Pages;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNtechCoupon extends EditRecord
{
    protected static string $resource = NtechCouponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
