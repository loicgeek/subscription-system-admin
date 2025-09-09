<?php

namespace NtechServices\SubscriptionSystemAdmin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource;

class SubscriptionSystemAdminPlugin implements Plugin
{
    protected bool $hasSubscriptionFeatureUsage = true;
    protected bool $hasCoupons = true;

    public function getId(): string
    {
        return 'ntech-subscription-system-admin';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            NtechPlanResource::class,
            NtechFeatureResource::class,
            NtechSubscriptionResource::class,
        ]);

        // Conditionally register optional resources
        if ($this->hasSubscriptionFeatureUsage()) {
            $panel->resources([
                NtechSubscriptionFeatureUsageResource::class,
            ]);
        }

        if ($this->hasCoupons()) {
            $panel->resources([
                NtechCouponResource::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        // Any boot logic here
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    // Configuration methods
    public function subscriptionFeatureUsage(bool $condition = true): static
    {
        $this->hasSubscriptionFeatureUsage = $condition;
        return $this;
    }

    public function hasSubscriptionFeatureUsage(): bool
    {
        return $this->hasSubscriptionFeatureUsage;
    }

    public function coupons(bool $condition = true): static
    {
        $this->hasCoupons = $condition;
        return $this;
    }

    public function hasCoupons(): bool
    {
        return $this->hasCoupons;
    }
}