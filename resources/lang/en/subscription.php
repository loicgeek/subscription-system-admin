<?php  

return [
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
];