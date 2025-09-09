<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;
class CreateNtechPlan extends CreateRecord
{
    protected static string $resource = NtechPlanResource::class;

    protected function afterCreate(): void
    {
        $features = $this->data['features'] ?? [];
    
        foreach ($features as $feature) {
            if (isset($feature['feature_id'])) { // Use feature_id consistently
                $this->record->features()->attach($feature['feature_id'], [
                    'value' => $feature['value'] ?? '',
                    'is_soft_limit' => $feature['is_soft_limit'] ?? false,
                    'overage_price' => $feature['overage_price'] ?? null,
                    'overage_currency' => $feature['overage_currency'] ?? null,
                ]);
            }
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove features from main data since we handle them separately
        unset($data['features']);
        return $data;
    }
}
