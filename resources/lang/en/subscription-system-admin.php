<?php

return [
    'general' => [
        'fields' => [
            'id' => 'ID',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ],
        'actions' => 'Actions',
        'yes' => 'Yes',
        'no' => 'No',
        'copy_suffix' => '(Copy)',
    ],

    'plan' => [
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
    ],

    'feature' => [
        'navigation_label' => 'Features',
        'singular' => 'Feature',
        'plural' => 'Features',

        'fields' => [
            'name' => 'Feature Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'default_value' => 'Default Value',
            'active' => 'Active',
        ],

        'placeholders' => [
            'name' => 'API Access',
            'slug' => 'api-access',
            'description' => 'Access to our powerful API with unlimited requests',
            'default_value' => '0, false, or empty',
        ],

        'help' => [
            'slug' => 'Unique identifier for the feature',
            'default_value' => 'Default value when not included in a plan',
            'active' => 'Whether this feature is available for assignment',
        ],

        'table' => [
            'default' => 'Default',
            'none' => 'None',
        ],

        'filters' => [
            'active_status' => 'Active Status',
        ],

        'actions' => [
            'new_feature' => 'New Feature',
            'activate' => 'Activate',
            'deactivate' => 'Deactivate',
            'duplicate' => 'Duplicate',
        ],

        'bulk_actions' => [
            'activate_selected' => 'Activate Selected',
            'deactivate_selected' => 'Deactivate Selected',
        ],

        'notifications' => [
            'activated' => 'Feature activated',
            'deactivated' => 'Feature deactivated',
            'status_changed' => "Feature ':name' has been :status.",
            'duplicated' => 'Feature duplicated',
            'feature_duplicated' => "New feature ':name' has been created.",
        ],

        'messages' => [
            'slug_copied' => 'Feature slug copied!',
        ],

        'empty_state' => [
            'create_first' => 'Create your first feature',
        ],

        'global_search' => [
            'status' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'default' => 'Default',
        ],
    ],

    'billing_cycle' => [
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ],

    'usage' => [
        'navigation_label' => 'Feature Usage',
        'singular' => 'Usage',
        'plural' => 'Usages',
        'unlimited' => 'Unlimited',

        'fields' => [
            'subscription' => 'Subscription',
            'feature' => 'Feature',
            'used' => 'Used',
            'limit' => 'Limit',
            'period_start' => 'Period Start',
            'period_end' => 'Period End',
            'usage_percentage' => 'Usage Percentage',
            'status' => 'Status',
            'project' => 'Project',
            'additional_limit' => 'Additional Limit',
        ],

        'status' => [
            'na' => 'N/A',
            'unlimited_na' => 'N/A (Unlimited)',
            'unlimited' => 'Unlimited',
            'over_limit' => 'Over Limit',
            'near_limit' => 'Near Limit',
            'within_limit' => 'Within Limit',
        ],

        'table' => [
            'no_project' => 'No project',
            'usage_percent' => 'Usage %',
            'na' => 'N/A',
        ],

        'filters' => [
            'feature' => 'Feature',
            'project' => 'Project',
            'over_limit' => 'Over Limit',
            'near_limit' => 'Near Limit (80%+)',
            'unlimited' => 'Unlimited Features',
            'current_period' => 'Current Period',
            'period_from' => 'Period From',
            'period_until' => 'Period Until',
        ],

        'actions' => [
            'reset_usage' => 'Reset Usage',
            'extend_limit' => 'Extend Limit',
        ],

        'bulk_actions' => [
            'reset_usage' => 'Reset Usage',
        ],

        'notifications' => [
            'usage_reset' => 'Usage reset',
            'usage_reset_message' => "Usage for feature ':feature' has been reset to 0.",
            'limit_extended' => 'Limit extended',
            'limit_extended_message' => 'Limit increased by :amount units.',
        ],

        'confirmations' => [
            'reset_usage' => 'Are you sure you want to reset the usage counter to 0?',
        ],

        'help' => [
            'additional_limit' => 'Amount to add to current limit',
        ],

        'global_search' => [
            'project' => 'Project',
            'used' => 'Used',
            'usage' => 'Usage',
            'period' => 'Period',
            'na' => 'N/A',
        ],
    ],

    'coupon' => [
        'navigation_label' => 'Coupons',
        'singular' => 'Coupon',
        'plural' => 'Coupons',
        'copy_suffix' => 'COPY',

        'fields' => [
            'code' => 'Coupon Code',
            'discount_amount' => 'Discount Amount',
            'discount_type' => 'Discount Type',
            'expires_at' => 'Expires At',
            'usage_limit' => 'Usage Limit',
            'used_count' => 'Times Used',
            'active' => 'Active',
            'description' => 'Description',
        ],

        'discount_types' => [
            'fixed' => 'Fixed Amount',
            'percentage' => 'Percentage',
        ],

        'placeholders' => [
            'code' => 'SAVE20',
            'discount_amount' => '10.00 or 20',
            'expires_at' => 'Select expiry date',
            'usage_limit' => 'Leave empty for unlimited',
            'description' => 'Optional description for internal use',
        ],

        'table' => [
            'discount' => 'Discount',
            'type' => 'Type',
            'unlimited' => 'Unlimited',
            'used' => 'Used',
            'expires' => 'Expires',
            'never' => 'Never',
        ],

        'filters' => [
            'discount_type' => 'Discount Type',
            'active_status' => 'Active Status',
            'expired' => 'Expired Coupons',
            'expires_soon' => 'Expires in 7 days',
            'expires_after' => 'Expires After',
            'expires_before' => 'Expires Before',
        ],

        'actions' => [
            'new_coupon' => 'New Coupon',
            'activate' => 'Activate',
            'deactivate' => 'Deactivate',
            'duplicate' => 'Duplicate',
        ],

        'bulk_actions' => [
            'activate_selected' => 'Activate Selected',
            'deactivate_selected' => 'Deactivate Selected',
        ],

        'notifications' => [
            'activated' => 'Coupon activated',
            'deactivated' => 'Coupon deactivated',
            'status_changed' => "Coupon ':code' has been :status.",
            'duplicated' => 'Coupon duplicated',
            'coupon_duplicated' => "New coupon ':code' has been created.",
        ],

        'messages' => [
            'code_copied' => 'Coupon code copied!',
        ],

        'global_search' => [
            'type' => 'Type',
            'discount' => 'Discount',
            'status' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'used' => 'Used',
        ],

        'empty_state' => [
            'create_first' => 'Create your first coupon',
        ],
    ],

    'subscription' => [
        'navigation_label' => 'Subscriptions',
        'singular' => 'Subscription',
        'plural' => 'Subscriptions',

        'fields' => [
            'project' => 'Project',
            'plan' => 'Plan',
            'plan_price' => 'Plan Price',
            'coupon' => 'Coupon',
            'start_date' => 'Start Date',
            'next_billing_date' => 'Next Billing Date',
            'amount_due' => 'Amount Due',
            'currency' => 'Currency',
            'status' => 'Status',
            'trial_ends_at' => 'Trial Ends At',
            'canceled_at' => 'Canceled At',
            'cancellation_reason' => 'Cancellation Reason',
            'price' => 'Price',
            'billing' => 'Billing',
            'trial_days' => 'Additional Trial Days',
        ],

        'status' => [
            'active' => 'Active',
            'canceled' => 'Canceled',
            'paused' => 'Paused',
            'expired' => 'Expired',
            'pending' => 'Pending',
            'trial' => 'Trial',
        ],

        'table' => [
            'no_project' => 'No project',
            'no_coupon' => 'No coupon',
            'started' => 'Started',
            'next_billing' => 'Next Billing',
            'trial_ends' => 'Trial Ends',
            'no_trial' => 'No trial',
            'canceled' => 'Canceled',
            'active' => 'Active',
        ],

        'filters' => [
            'status' => 'Status',
            'plan' => 'Plan',
            'project' => 'Project',
            'billing_cycle' => 'Billing Cycle',
            'with_coupon' => 'With Coupon',
            'overdue_billing' => 'Overdue Billing',
            'due_soon' => 'Due in 7 days',
            'trial_ending' => 'Trial Ending Soon',
            'created_after' => 'Created After',
            'created_before' => 'Created Before',
        ],

        'actions' => [
            'new_subscription' => 'New Subscription',
            'cancel' => 'Cancel',
            'pause' => 'Pause',
            'resume' => 'Resume',
            'extend_trial' => 'Extend Trial',
        ],

        'bulk_actions' => [
            'pause_selected' => 'Pause Selected',
            'cancel_selected' => 'Cancel Selected',
        ],

        'notifications' => [
            'canceled' => 'Subscription canceled',
            'subscription_canceled' => 'Subscription #:id has been canceled.',
            'paused' => 'Subscription paused',
            'subscription_paused' => 'Subscription #:id has been paused.',
            'resumed' => 'Subscription resumed',
            'subscription_resumed' => 'Subscription #:id has been resumed.',
            'trial_extended' => 'Trial extended',
            'trial_extended_message' => 'Trial extended by :days days.',
        ],

        'placeholders' => [
            'cancellation_reason' => 'Please provide a reason for cancellation',
            'bulk_cancellation_reason' => 'Please provide a reason for bulk cancellation',
        ],

        'global_search' => [
            'title' => 'Subscription #:id - :project',
            'no_plan' => 'No Plan',
        ],

        'empty_state' => [
            'create_first' => 'Create your first subscription',
        ],

        'relations' => [
            'feature_usages' => [
                'title' => 'Feature Usages',
                'unlimited' => 'Unlimited',
                'columns' => [
                    'feature' => 'Feature',
                    'description' => 'Description',
                    'used' => 'Used',
                    'limit' => 'Limit',
                    'remaining' => 'Remaining',
                    'usage_percentage' => 'Usage %',
                    'overage_count' => 'Overage Count',
                    'period_start' => 'Period Start',
                    'period_end' => 'Period End',
                ],
                'filters' => [
                    'at_limit' => 'At Limit',
                    'unlimited' => 'Unlimited Features',
                ],
                'actions' => [
                    'reset_usage' => 'Reset Usage',
                    'view_all_features' => 'View All Features Summary',
                ],
            ],
            'histories' => [
                'title' => 'Subscription History',
                'columns' => [
                    'status' => 'Status',
                    'plan' => 'Plan',
                    'price' => 'Price',
                    'billing_cycle' => 'Billing Cycle',
                ],
            ],
        ],
    ],

    'currency' => [
        'usd' => 'USD ($)',
        'eur' => 'EUR (€)',
        'gbp' => 'GBP (£)',
        'cad' => 'CAD (C$)',
    ],
];