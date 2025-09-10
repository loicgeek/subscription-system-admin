<?php

namespace NtechServices\SubscriptionSystemAdmin\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use NtechServices\SubscriptionSystem\Models\Plan;
use NtechServices\SubscriptionSystemAdmin\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::select([
            'id', 'name', 'slug', 'description', 'order', 'popular', 
            'trial_value', 'trial_cycle', 'created_at', 'updated_at'
        ])
        ->with([
            'features:id,name,slug,description',
            'planPrices:id,plan_id,price,currency,billing_cycle,is_active,created_at,updated_at',
            'planPrices.planPriceFeatureOverrides:id,plan_price_id,feature_id,value,is_soft_limit,overage_price,overage_currency',
            'planPrices.planPriceFeatureOverrides.feature:id,name,slug'
        ])
        ->orderBy('order')
        ->get();

        $formattedPlans = $plans->map(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'description' => $plan->description,
                'order' => $plan->order,
                'popular' => $plan->popular,
                'trial_value' => $plan->trial_value,
                'trial_cycle' => $plan->trial_cycle,
                'created_at' => $plan->created_at,
                'updated_at' => $plan->updated_at,
                
                // Features with pivot data
                'features' => $plan->features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'name' => $feature->name,
                        'slug' => $feature->slug,
                        'description' => $feature->description,
                        'value' => $feature->pivot->value,
                        'is_soft_limit' => $feature->pivot->is_soft_limit,
                        'overage_price' => $feature->pivot->overage_price,
                        'overage_currency' => $feature->pivot->overage_currency,
                    ];
                }),
                
                // Prices with overrides
                'prices' => $plan->planPrices->map(function ($price) {
                    return [
                        'id' => $price->id,
                        'price' => $price->price,
                        'currency' => $price->currency,
                        'billing_cycle' => $price->billing_cycle,
                        'is_active' => $price->is_active,
                        'created_at' => $price->created_at,
                        'updated_at' => $price->updated_at,
                        
                        // Feature overrides for this price
                        'feature_overrides' => $price->planPriceFeatureOverrides->map(function ($override) {
                            return [
                                'id' => $override->id,
                                'feature_id' => $override->feature_id,
                                'feature_name' => $override->feature->name,
                                'value' => $override->value,
                                'is_soft_limit' => $override->is_soft_limit,
                                'overage_price' => $override->overage_price,
                                'overage_currency' => $override->overage_currency,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedPlans
        ]);
    }

    public function show(Plan $plan): JsonResponse
    {
        $plan->load([
            'features',
            'planPrices.planPriceFeatureOverrides',
            'planPrices.planPriceFeatureOverrides.feature'
        ]);

        $formattedPlan = [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'description' => $plan->description,
            'order' => $plan->order,
            'popular' => $plan->popular,
            'trial_value' => $plan->trial_value,
            'trial_cycle' => $plan->trial_cycle,
            'created_at' => $plan->created_at,
            'updated_at' => $plan->updated_at,
            
            // Features with pivot data
            'features' => $plan->features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'slug' => $feature->slug,
                    'description' => $feature->description,
                    'value' => $feature->pivot->value,
                    'is_soft_limit' => $feature->pivot->is_soft_limit,
                    'overage_price' => $feature->pivot->overage_price,
                    'overage_currency' => $feature->pivot->overage_currency,
                ];
            }),
            
            // Prices with overrides
            'prices' => $plan->planPrices->map(function ($price) {
                return [
                    'id' => $price->id,
                    'price' => $price->price,
                    'currency' => $price->currency,
                    'billing_cycle' => $price->billing_cycle,
                    'is_active' => $price->is_active,
                    'created_at' => $price->created_at,
                    'updated_at' => $price->updated_at,
                    
                    // Feature overrides for this price
                    'feature_overrides' => $price->planPriceFeatureOverrides->map(function ($override) {
                        return [
                            'id' => $override->id,
                            'feature_id' => $override->feature_id,
                            'feature_name' => $override->feature->name,
                            'value' => $override->value,
                            'is_soft_limit' => $override->is_soft_limit,
                            'overage_price' => $override->overage_price,
                            'overage_currency' => $override->overage_currency,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $formattedPlan
        ]);
    }

    public function features(Plan $plan): JsonResponse
    {
        $plan->load([
            'features',
            'planPrices.planPriceFeatureOverrides',
            'planPrices.planPriceFeatureOverrides.feature'
        ]);

        $mergedData = [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'description' => $plan->description,
            'order' => $plan->order,
            'popular' => $plan->popular,
            'trial_value' => $plan->trial_value,
            'trial_cycle' => $plan->trial_cycle,
            
            'prices' => $plan->planPrices->map(function ($price) use ($plan) {
                // Get base features from plan
                $baseFeatures = $plan->features->keyBy('id');
                
                // Get overrides for this price
                $overrides = $price->planPriceFeatureOverrides->keyBy('feature_id');
                
                // Merge features with overrides
                $mergedFeatures = $baseFeatures->map(function ($feature) use ($overrides) {
                    $featureData = [
                        'id' => $feature->id,
                        'name' => $feature->name,
                        'slug' => $feature->slug,
                        'description' => $feature->description,
                        // Default values from plan
                        'value' => $feature->pivot->value,
                        'is_soft_limit' => $feature->pivot->is_soft_limit,
                        'overage_price' => $feature->pivot->overage_price,
                        'overage_currency' => $feature->pivot->overage_currency,
                        'is_overridden' => false,
                    ];
                    
                    // Apply override if exists
                    if ($overrides->has($feature->id)) {
                        $override = $overrides->get($feature->id);
                        $featureData['value'] = $override->value;
                        $featureData['is_soft_limit'] = $override->is_soft_limit;
                        $featureData['overage_price'] = $override->overage_price;
                        $featureData['overage_currency'] = $override->overage_currency;
                        $featureData['is_overridden'] = true;
                    }
                    
                    return $featureData;
                });

                return [
                    'id' => $price->id,
                    'price' => $price->price,
                    'currency' => $price->currency,
                    'billing_cycle' => $price->billing_cycle,
                    'is_active' => $price->is_active,
                    'features' => $mergedFeatures->values(), // Reset keys
                ];
            }),
        ];

        return response()->json([
            'success' => true,
            'data' => $mergedData
        ]);
    }
}