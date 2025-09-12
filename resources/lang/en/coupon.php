<?php 

return [
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
];