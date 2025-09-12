<?php 

return [
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
];