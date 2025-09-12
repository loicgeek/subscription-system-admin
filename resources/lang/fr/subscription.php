<?php  

return [
    'navigation_label' => 'Abonnements',
    'singular' => 'Abonnement',
    'plural' => 'Abonnements',

    'fields' => [
        'project' => 'Projet',
        'plan' => 'Plan',
        'plan_price' => 'Prix du Plan',
        'coupon' => 'Coupon',
        'start_date' => 'Date de Début',
        'next_billing_date' => 'Prochaine Date de Facturation',
        'amount_due' => 'Montant Dû',
        'currency' => 'Devise',
        'status' => 'Statut',
        'trial_ends_at' => 'L\'Essai se Termine le',
        'canceled_at' => 'Annulé le',
        'cancellation_reason' => 'Raison d\'Annulation',
        'price' => 'Prix',
        'billing' => 'Facturation',
        'trial_days' => 'Jours d\'Essai Supplémentaires',
    ],

    'status' => [
        'active' => 'Actif',
        'canceled' => 'Annulé',
        'paused' => 'En Pause',
        'expired' => 'Expiré',
        'pending' => 'En Attente',
        'trial' => 'Essai',
    ],

    'table' => [
        'no_project' => 'Aucun projet',
        'no_coupon' => 'Aucun coupon',
        'started' => 'Commencé',
        'next_billing' => 'Prochaine Facturation',
        'trial_ends' => 'Fin d\'Essai',
        'no_trial' => 'Aucun essai',
        'canceled' => 'Annulé',
        'active' => 'Actif',
    ],

    'filters' => [
        'status' => 'Statut',
        'plan' => 'Plan',
        'project' => 'Projet',
        'billing_cycle' => 'Cycle de Facturation',
        'with_coupon' => 'Avec Coupon',
        'overdue_billing' => 'Facturation en Retard',
        'due_soon' => 'Dû dans 7 jours',
        'trial_ending' => 'Essai se Terminant Bientôt',
        'created_after' => 'Créé Après',
        'created_before' => 'Créé Avant',
    ],

    'actions' => [
        'new_subscription' => 'Nouvel Abonnement',
        'cancel' => 'Annuler',
        'pause' => 'Mettre en Pause',
        'resume' => 'Reprendre',
        'extend_trial' => 'Prolonger l\'Essai',
    ],

    'bulk_actions' => [
        'pause_selected' => 'Mettre en Pause la Sélection',
        'cancel_selected' => 'Annuler la Sélection',
    ],

    'notifications' => [
        'canceled' => 'Abonnement annulé',
        'subscription_canceled' => 'L\'abonnement #:id a été annulé.',
        'paused' => 'Abonnement mis en pause',
        'subscription_paused' => 'L\'abonnement #:id a été mis en pause.',
        'resumed' => 'Abonnement repris',
        'subscription_resumed' => 'L\'abonnement #:id a été repris.',
        'trial_extended' => 'Essai prolongé',
        'trial_extended_message' => 'Essai prolongé de :days jours.',
    ],

    'placeholders' => [
        'cancellation_reason' => 'Veuillez fournir une raison d\'annulation',
        'bulk_cancellation_reason' => 'Veuillez fournir une raison d\'annulation en masse',
    ],

    'global_search' => [
        'title' => 'Abonnement #:id - :project',
        'no_plan' => 'Aucun Plan',
    ],

    'empty_state' => [
        'create_first' => 'Créez votre premier abonnement',
    ],

    'relations' => [
        'feature_usages' => [
            'title' => 'Utilisations des Fonctionnalités',
            'unlimited' => 'Illimité',
            'columns' => [
                'feature' => 'Fonctionnalité',
                'description' => 'Description',
                'used' => 'Utilisé',
                'limit' => 'Limite',
                'remaining' => 'Restant',
                'usage_percentage' => 'Utilisation %',
                'overage_count' => 'Nombre de Dépassements',
                'period_start' => 'Début de Période',
                'period_end' => 'Fin de Période',
            ],
            'filters' => [
                'at_limit' => 'À la Limite',
                'unlimited' => 'Fonctionnalités Illimitées',
            ],
            'actions' => [
                'reset_usage' => 'Réinitialiser l\'Utilisation',
                'view_all_features' => 'Voir le Résumé de Toutes les Fonctionnalités',
            ],
        ],
        'histories' => [
            'title' => 'Historique des Abonnements',
            'columns' => [
                'status' => 'Statut',
                'plan' => 'Plan',
                'price' => 'Prix',
                'billing_cycle' => 'Cycle de Facturation',
            ],
        ],
    ],
];