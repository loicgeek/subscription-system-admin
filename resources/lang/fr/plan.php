<?php 

return [
    'navigation_label' => 'Plans',
    'singular' => 'Plan',
    'plural' => 'Plans',

    'fields' => [
        'name' => 'Nom du Plan',
        'slug' => 'Slug',
        'description' => 'Description',
        'order' => 'Ordre d\'Affichage',
        'popular' => 'Marquer comme Populaire',
        'trial_value' => 'Durée d\'Essai',
        'trial_cycle' => 'Cycle d\'Essai',
        'trial' => 'Essai',
        'value' => 'Valeur',
        'soft_limit' => 'Limite Souple',
        'overage_price' => 'Prix de Dépassement',
        'overage_currency' => 'Devise de Dépassement',
        'currency' => 'Devise',
        'price' => 'Prix',
        'billing_cycle' => 'Cycle de Facturation',
        'override_value' => 'Valeur de Substitution',
    ],

    'placeholders' => [
        'name' => 'Plan Basique',
        'description' => 'Parfait pour les petites équipes et startups',
        'value' => '100, vrai, illimité',
    ],

    'help' => [
        'popular' => 'Ce plan sera mis en évidence dans les tableaux de prix',
        'override_value' => 'Ceci remplacera la valeur par défaut du plan',
    ],

    'sections' => [
        'features' => 'Fonctionnalités du Plan',
        'prices' => 'Prix du Plan',
        'feature_overrides' => 'Substitutions de Fonctionnalités',
    ],

    'table' => [
        'prices' => 'Prix',
    ],

    'filters' => [
        'popular' => 'Plans Populaires',
        'trial_cycle' => 'Cycle d\'Essai',
    ],

    'actions' => [
        'new_plan' => 'Nouveau Plan',
        'add_feature' => 'Ajouter une Fonctionnalité',
        'add_price' => 'Ajouter un Prix',
        'add_feature_override' => 'Ajouter une Substitution de Fonctionnalité',
        'manage_features' => 'Gérer les Fonctionnalités',
        'manage_prices' => 'Gérer les Prix',
        'mark_popular' => 'Marquer comme Populaire',
        'remove_popular' => 'Retirer Populaire',
        'duplicate' => 'Dupliquer le Plan',
    ],

    'bulk_actions' => [
        'mark_popular' => 'Marquer comme Populaire',
        'unmark_popular' => 'Retirer Populaire',
    ],

    'notifications' => [
        'marked_popular' => 'Plan marqué comme populaire',
        'unmarked_popular' => 'Plan retiré des populaires',
        'plan_status_changed' => "Le plan ':name' a été :status.",
        'duplicated' => 'Plan dupliqué',
        'plan_duplicated' => "Le nouveau plan ':name' a été créé.",
    ],

    'repeater' => [
        'new_feature' => 'Nouvelle Fonctionnalité',
        'unknown_feature' => 'Fonctionnalité Inconnue',
        'new_price' => 'Nouveau Prix',
        'new_override' => 'Nouvelle Substitution',
        'overrides_count' => ':count substitutions',
    ],

    'empty_state' => [
        'create_first' => 'Créez votre premier plan',
    ],

    'relations' => [
        'feature_values' => [
            'title' => 'Valeurs des Fonctionnalités',
            'fields' => [
                'feature' => 'Fonctionnalité',
                'value' => 'Valeur',
            ],
            'columns' => [
                'feature' => 'Fonctionnalité',
                'description' => 'Description',
                'value' => 'Valeur',
            ],
        ],
        'prices' => [
            'title' => 'Prix',
            'fields' => [
                'price' => 'Prix',
                'currency' => 'Devise',
                'billing_cycle' => 'Cycle de Facturation',
            ],
            'columns' => [
                'price' => 'Prix',
                'currency' => 'Devise',
                'billing_cycle' => 'Cycle de Facturation',
            ],
            'billing_cycles' => [
                'monthly' => 'Mensuel',
                'quarterly' => 'Trimestriel',
                'yearly' => 'Annuel',
            ],
        ],
    ],
];