<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource;

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
