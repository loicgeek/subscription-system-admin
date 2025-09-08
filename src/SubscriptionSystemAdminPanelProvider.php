<?php

namespace NtechServices\SubscriptionSystemAdmin;
use Filament\Panel;
use Filament\PanelProvider;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource;

class SubscriptionSystemAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('subscription-system-admin')
            ->path('subscription-system-admin')
            ->resources([
                NtechPlanResource::class,
                NtechFeatureResource::class,
                NtechCouponResource::class,
                NtechSubscriptionFeatureUsageResource::class,
                NtechSubscriptionResource::class,
            ])
            ->pages([
                // ...
            ])
            ->widgets([
                // ...
            ])
            ->middleware([
                // ...
            ])
            ->authMiddleware([
                // ...
            ]);
    }
}