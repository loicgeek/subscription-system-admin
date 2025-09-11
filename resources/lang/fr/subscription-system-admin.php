<?php

return [
    'general' => [
        'fields' => [
            'id' => 'ID',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
        ],
        'actions' => 'Actions',
        'yes' => 'Oui',
        'no' => 'Non',
        'copy_suffix' => '(Copie)',
    ],

    'plan' => [
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
    ],

    'feature' => [
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
    ],

    'billing_cycle' => [
        'daily' => 'Quotidien',
        'weekly' => 'Hebdomadaire',
        'monthly' => 'Mensuel',
        'yearly' => 'Annuel',
    ],

    'usage' => [
        'navigation_label' => 'Utilisation des Fonctionnalités',
        'singular' => 'Utilisation',
        'plural' => 'Utilisations',
        'unlimited' => 'Illimité',

        'fields' => [
            'subscription' => 'Abonnement',
            'feature' => 'Fonctionnalité',
            'used' => 'Utilisé',
            'limit' => 'Limite',
            'period_start' => 'Début de Période',
            'period_end' => 'Fin de Période',
            'usage_percentage' => 'Pourcentage d\'Utilisation',
            'status' => 'Statut',
            'project' => 'Projet',
            'additional_limit' => 'Limite Supplémentaire',
        ],

        'status' => [
            'na' => 'N/D',
            'unlimited_na' => 'N/D (Illimité)',
            'unlimited' => 'Illimité',
            'over_limit' => 'Dépassement de Limite',
            'near_limit' => 'Proche de la Limite',
            'within_limit' => 'Dans la Limite',
        ],

        'table' => [
            'no_project' => 'Aucun projet',
            'usage_percent' => 'Utilisation %',
            'na' => 'N/D',
        ],

        'filters' => [
            'feature' => 'Fonctionnalité',
            'project' => 'Projet',
            'over_limit' => 'Dépassement de Limite',
            'near_limit' => 'Proche de la Limite (80%+)',
            'unlimited' => 'Fonctionnalités Illimitées',
            'current_period' => 'Période Actuelle',
            'period_from' => 'Période À Partir De',
            'period_until' => 'Période Jusqu\'À',
        ],

        'actions' => [
            'reset_usage' => 'Réinitialiser l\'Utilisation',
            'extend_limit' => 'Étendre la Limite',
        ],

        'bulk_actions' => [
            'reset_usage' => 'Réinitialiser l\'Utilisation',
        ],

        'notifications' => [
            'usage_reset' => 'Utilisation réinitialisée',
            'usage_reset_message' => "L'utilisation de la fonctionnalité ':feature' a été réinitialisée à 0.",
            'limit_extended' => 'Limite étendue',
            'limit_extended_message' => 'Limite augmentée de :amount unités.',
        ],

        'confirmations' => [
            'reset_usage' => 'Êtes-vous sûr de vouloir réinitialiser le compteur d\'utilisation à 0 ?',
        ],

        'help' => [
            'additional_limit' => 'Quantité à ajouter à la limite actuelle',
        ],

        'global_search' => [
            'project' => 'Projet',
            'used' => 'Utilisé',
            'usage' => 'Utilisation',
            'period' => 'Période',
            'na' => 'N/D',
        ],
    ],

    'coupon' => [
        'navigation_label' => 'Coupons',
        'singular' => 'Coupon',
        'plural' => 'Coupons',
        'copy_suffix' => 'COPIE',

        'fields' => [
            'code' => 'Code du Coupon',
            'discount_amount' => 'Montant de Réduction',
            'discount_type' => 'Type de Réduction',
            'expires_at' => 'Expire le',
            'usage_limit' => 'Limite d\'Utilisation',
            'used_count' => 'Nombre d\'Utilisations',
            'active' => 'Actif',
            'description' => 'Description',
        ],

        'discount_types' => [
            'fixed' => 'Montant Fixe',
            'percentage' => 'Pourcentage',
        ],

        'placeholders' => [
            'code' => 'ECONOMIE20',
            'discount_amount' => '10.00 ou 20',
            'expires_at' => 'Sélectionner la date d\'expiration',
            'usage_limit' => 'Laisser vide pour illimité',
            'description' => 'Description optionnelle pour usage interne',
        ],

        'table' => [
            'discount' => 'Réduction',
            'type' => 'Type',
            'unlimited' => 'Illimité',
            'used' => 'Utilisé',
            'expires' => 'Expire',
            'never' => 'Jamais',
        ],

        'filters' => [
            'discount_type' => 'Type de Réduction',
            'active_status' => 'Statut Actif',
            'expired' => 'Coupons Expirés',
            'expires_soon' => 'Expire dans 7 jours',
            'expires_after' => 'Expire Après',
            'expires_before' => 'Expire Avant',
        ],

        'actions' => [
            'new_coupon' => 'Nouveau Coupon',
            'activate' => 'Activer',
            'deactivate' => 'Désactiver',
            'duplicate' => 'Dupliquer',
        ],

        'bulk_actions' => [
            'activate_selected' => 'Activer la Sélection',
            'deactivate_selected' => 'Désactiver la Sélection',
        ],

        'notifications' => [
            'activated' => 'Coupon activé',
            'deactivated' => 'Coupon désactivé',
            'status_changed' => "Le coupon ':code' a été :status.",
            'duplicated' => 'Coupon dupliqué',
            'coupon_duplicated' => "Le nouveau coupon ':code' a été créé.",
        ],

        'messages' => [
            'code_copied' => 'Code du coupon copié !',
        ],

        'global_search' => [
            'type' => 'Type',
            'discount' => 'Réduction',
            'status' => 'Statut',
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'used' => 'Utilisé',
        ],

        'empty_state' => [
            'create_first' => 'Créez votre premier coupon',
        ],
    ],

    'subscription' => [
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
    ],

    'currency' => [
        'usd' => 'USD ($)',
        'eur' => 'EUR (€)',
        'gbp' => 'GBP (£)',
        'cad' => 'CAD (C$)',
    ],
];