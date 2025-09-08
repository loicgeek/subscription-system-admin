<?php

namespace NtechServices\SubscriptionSystemAdmin;
use Filament\Panel;
use Filament\PanelProvider;

class SubscriptionSystemAdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('subscription-system-admin')
            ->path('subscription-system-admin')
            ->resources([
                // ...
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