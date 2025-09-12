<?php 

return [
    'navigation_label' => 'Plans',
    'singular' => 'Plan',
    'plural' => 'Plans',

    'fields' => [
        'name' => 'Plan Name',
        'slug' => 'Slug',
        'description' => 'Description',
        'order' => 'Display Order',
        'popular' => 'Mark as Popular',
        'trial_value' => 'Trial Duration',
        'trial_cycle' => 'Trial Cycle',
        'trial' => 'Trial',
        'value' => 'Value',
        'soft_limit' => 'Soft Limit',
        'overage_price' => 'Overage Price',
        'overage_currency' => 'Overage Currency',
        'currency' => 'Currency',
        'price' => 'Price',
        'billing_cycle' => 'Billing Cycle',
        'override_value' => 'Override Value',
    ],

    'placeholders' => [
        'name' => 'Basic Plan',
        'description' => 'Perfect for small teams and startups',
        'value' => '100, true, unlimited',
    ],

    'help' => [
        'popular' => 'This plan will be highlighted in pricing tables',
        'override_value' => 'This will override the plan default value',
    ],

    'sections' => [
        'features' => 'Plan Features',
        'prices' => 'Plan Prices',
        'feature_overrides' => 'Feature Overrides',
    ],

    'table' => [
        'prices' => 'Prices',
    ],

    'filters' => [
        'popular' => 'Popular Plans',
        'trial_cycle' => 'Trial Cycle',
    ],

    'actions' => [
        'new_plan' => 'New Plan',
        'add_feature' => 'Add Feature',
        'add_price' => 'Add Price',
        'add_feature_override' => 'Add Feature Override',
        'manage_features' => 'Manage Features',
        'manage_prices' => 'Manage Prices',
        'mark_popular' => 'Mark as Popular',
        'remove_popular' => 'Remove Popular',
        'duplicate' => 'Duplicate Plan',
    ],

    'bulk_actions' => [
        'mark_popular' => 'Mark as Popular',
        'unmark_popular' => 'Remove Popular',
    ],

    'notifications' => [
        'marked_popular' => 'Plan marked as popular',
        'unmarked_popular' => 'Plan unmarked as popular',
        'plan_status_changed' => "Plan ':name' has been :status.",
        'duplicated' => 'Plan duplicated',
        'plan_duplicated' => "New plan ':name' has been created.",
    ],

    'repeater' => [
        'new_feature' => 'New Feature',
        'unknown_feature' => 'Unknown Feature',
        'new_price' => 'New Price',
        'new_override' => 'New Override',
        'overrides_count' => ':count overrides',
    ],

    'empty_state' => [
        'create_first' => 'Create your first plan',
    ],

    'relations' => [
        'feature_values' => [
            'title' => 'Feature Values',
            'fields' => [
                'feature' => 'Feature',
                'value' => 'Value',
            ],
            'columns' => [
                'feature' => 'Feature',
                'description' => 'Description',
                'value' => 'Value',
            ],
        ],
        'prices' => [
            'title' => 'Prices',
            'fields' => [
                'price' => 'Price',
                'currency' => 'Currency',
                'billing_cycle' => 'Billing Cycle',
            ],
            'columns' => [
                'price' => 'Price',
                'currency' => 'Currency',
                'billing_cycle' => 'Billing Cycle',
            ],
            'billing_cycles' => [
                'monthly' => 'Monthly',
                'quarterly' => 'Quarterly',
                'yearly' => 'Yearly',
            ],
        ],
    ],
];