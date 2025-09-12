<?php 

return [
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
];