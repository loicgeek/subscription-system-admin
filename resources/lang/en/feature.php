<?php 

return  [
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
];