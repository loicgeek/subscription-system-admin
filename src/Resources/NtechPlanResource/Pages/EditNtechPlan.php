<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;

class EditNtechPlan extends EditRecord
{
    protected static string $resource = NtechPlanResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $features = [];
    
        foreach ($this->record->features as $feature) {
            $uuid = (string) \Illuminate\Support\Str::uuid();        
            $features[$uuid] = [
                'feature_id' => (int) $feature->id,
                'value' => $feature->pivot->value ?? '',
                'is_soft_limit' => (bool) ($feature->pivot->is_soft_limit ?? false),
                'overage_price' => $feature->pivot->overage_price,
                'overage_currency' => $feature->pivot->overage_currency,
            ];
        }
        
        // Only set features if there are any, otherwise let it be empty
        if (!empty($features)) {
            $data['features'] = $features;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $features = $this->data['features'] ?? [];
    
        // Detach existing features
        $this->record->features()->detach();
    
        // Attach new ones
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove features from the main data since we handle them separately
        unset($data['features']);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}