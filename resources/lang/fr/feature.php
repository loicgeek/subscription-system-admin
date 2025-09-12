<?php 

return  [
    'navigation_label' => 'Fonctionnalités',
    'singular' => 'Fonctionnalité',
    'plural' => 'Fonctionnalités',

    'fields' => [
        'name' => 'Nom de la Fonctionnalité',
        'slug' => 'Slug',
        'description' => 'Description',
        'default_value' => 'Valeur par Défaut',
        'active' => 'Actif',
    ],

    'placeholders' => [
        'name' => 'Accès API',
        'slug' => 'acces-api',
        'description' => 'Accès à notre API puissante avec des requêtes illimitées',
        'default_value' => '0, faux, ou vide',
    ],

    'help' => [
        'slug' => 'Identifiant unique pour la fonctionnalité',
        'default_value' => 'Valeur par défaut quand non incluse dans un plan',
        'active' => 'Si cette fonctionnalité est disponible pour l\'attribution',
    ],

    'table' => [
        'default' => 'Défaut',
        'none' => 'Aucun',
    ],

    'filters' => [
        'active_status' => 'Statut Actif',
    ],

    'actions' => [
        'new_feature' => 'Nouvelle Fonctionnalité',
        'activate' => 'Activer',
        'deactivate' => 'Désactiver',
        'duplicate' => 'Dupliquer',
    ],

    'bulk_actions' => [
        'activate_selected' => 'Activer la Sélection',
        'deactivate_selected' => 'Désactiver la Sélection',
    ],

    'notifications' => [
        'activated' => 'Fonctionnalité activée',
        'deactivated' => 'Fonctionnalité désactivée',
        'status_changed' => "La fonctionnalité ':name' a été :status.",
        'duplicated' => 'Fonctionnalité dupliquée',
        'feature_duplicated' => "La nouvelle fonctionnalité ':name' a été créée.",
    ],

    'messages' => [
        'slug_copied' => 'Slug de la fonctionnalité copié !',
    ],

    'empty_state' => [
        'create_first' => 'Créez votre première fonctionnalité',
    ],

    'global_search' => [
        'status' => 'Statut',
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'default' => 'Défaut',
    ],
];