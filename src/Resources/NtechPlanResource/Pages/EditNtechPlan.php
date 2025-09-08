<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource;

class EditNtechPlan extends EditRecord
{
    protected static string $resource = NtechPlanResource::class;

    protected function afterSave(): void
    {
        $features = $this->data['features'] ?? [];

        // // Detach existing features
        $this->record->features()->detach();

        // // Attach new ones
        foreach ($features as $feature) {
            $this->record->features()->attach($feature['id'], [
                'value' => $feature['value'],
                'is_soft_limit' => $feature['is_soft_limit'],
                'overage_price' => $feature['overage_price'],
                'overage_currency' => $feature['overage_currency'],
            ]);
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        // Load features with UUID keys to match Filament's repeater structure
        $features = [];
        foreach ($this->record->features as $feature) {
            // Generate a UUID for each feature item (Filament's repeater expects this)
            $uuid = (string) \Illuminate\Support\Str::uuid();
            $features[$uuid] = [
                'id' => $feature->id,
                'value' => $feature->pivot->value,
                'is_soft_limit' => $feature->pivot->is_soft_limit,
                'overage_price' => $feature->pivot->overage_price,
                'overage_currency' => $feature->pivot->overage_currency,
            ];
        }

        $data['features'] = $features;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
