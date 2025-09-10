<?php

namespace NtechServices\SubscriptionSystemAdmin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'order' => $this->order,
            'popular' => $this->popular,
            'trial_value' => $this->trial_value,
            'trial_cycle' => $this->trial_cycle,
            
            'prices' => $this->planPrices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'price' => $price->price,
                    'currency' => $price->currency,
                    'billing_cycle' => $price->billing_cycle,
                    'is_active' => $price->is_active,
                    'features' => $this->getMergedFeatures($price),
                ];
            }),
        ];
    }
    
    private function getMergedFeatures($price)
    {
        // Get base features from plan
        $baseFeatures = $this->features->keyBy('id');
        
        // Get overrides for this price
        $overrides = $price->planPriceFeatureOverrides->keyBy('feature_id');
        
        return $baseFeatures->map(function ($feature) use ($overrides) {
            $featureData = [
                'id' => $feature->id,
                'name' => $feature->name,
                'slug' => $feature->slug,
                'description' => $feature->description,
                'value' => $feature->pivot->value,
                'is_soft_limit' => $feature->pivot->is_soft_limit,
                'overage_price' => $feature->pivot->overage_price,
                'overage_currency' => $feature->pivot->overage_currency,
                'is_overridden' => false,
            ];
            
            // Apply override if exists
            if ($overrides->has($feature->id)) {
                $override = $overrides->get($feature->id);
                $featureData = array_merge($featureData, [
                    'value' => $override->value,
                    'is_soft_limit' => $override->is_soft_limit,
                    'overage_price' => $override->overage_price,
                    'overage_currency' => $override->overage_currency,
                    'is_overridden' => true,
                ]);
            }
            
            return $featureData;
        })->values();
    }
}