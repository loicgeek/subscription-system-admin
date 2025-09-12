<?php 

return [
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
];