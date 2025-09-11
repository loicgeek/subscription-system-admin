<?php

return [
    'general' => [
        'fields' => [
            'id' => 'ID',
            'created_at' => 'Creado',
            'updated_at' => 'Actualizado',
        ],
        'actions' => 'Acciones',
        'yes' => 'Sí',
        'no' => 'No',
        'copy_suffix' => '(Copia)',
    ],

    'plan' => [
        'navigation_label' => 'Planes',
        'singular' => 'Plan',
        'plural' => 'Planes',

        'fields' => [
            'name' => 'Nombre del Plan',
            'slug' => 'Slug',
            'description' => 'Descripción',
            'order' => 'Orden de Visualización',
            'popular' => 'Marcar como Popular',
            'trial_value' => 'Duración de Prueba',
            'trial_cycle' => 'Ciclo de Prueba',
            'trial' => 'Prueba',
            'value' => 'Valor',
            'soft_limit' => 'Límite Flexible',
            'overage_price' => 'Precio de Exceso',
            'overage_currency' => 'Moneda de Exceso',
            'currency' => 'Moneda',
            'price' => 'Precio',
            'billing_cycle' => 'Ciclo de Facturación',
            'override_value' => 'Valor de Reemplazo',
        ],

        'placeholders' => [
            'name' => 'Plan Básico',
            'description' => 'Perfecto para equipos pequeños y startups',
            'value' => '100, verdadero, ilimitado',
        ],

        'help' => [
            'popular' => 'Este plan será destacado en las tablas de precios',
            'override_value' => 'Esto reemplazará el valor predeterminado del plan',
        ],

        'sections' => [
            'features' => 'Características del Plan',
            'prices' => 'Precios del Plan',
            'feature_overrides' => 'Reemplazos de Características',
        ],

        'table' => [
            'prices' => 'Precios',
        ],

        'filters' => [
            'popular' => 'Planes Populares',
            'trial_cycle' => 'Ciclo de Prueba',
        ],

        'actions' => [
            'new_plan' => 'Nuevo Plan',
            'add_feature' => 'Agregar Característica',
            'add_price' => 'Agregar Precio',
            'add_feature_override' => 'Agregar Reemplazo de Característica',
            'manage_features' => 'Gestionar Características',
            'manage_prices' => 'Gestionar Precios',
            'mark_popular' => 'Marcar como Popular',
            'remove_popular' => 'Quitar Popular',
            'duplicate' => 'Duplicar Plan',
        ],

        'bulk_actions' => [
            'mark_popular' => 'Marcar como Popular',
            'unmark_popular' => 'Quitar Popular',
        ],

        'notifications' => [
            'marked_popular' => 'Plan marcado como popular',
            'unmarked_popular' => 'Plan quitado de populares',
            'plan_status_changed' => "El plan ':name' ha sido :status.",
            'duplicated' => 'Plan duplicado',
            'plan_duplicated' => "El nuevo plan ':name' ha sido creado.",
        ],

        'repeater' => [
            'new_feature' => 'Nueva Característica',
            'unknown_feature' => 'Característica Desconocida',
            'new_price' => 'Nuevo Precio',
            'new_override' => 'Nuevo Reemplazo',
            'overrides_count' => ':count reemplazos',
        ],

        'empty_state' => [
            'create_first' => 'Crea tu primer plan',
        ],

        'relations' => [
            'feature_values' => [
                'title' => 'Valores de Características',
                'fields' => [
                    'feature' => 'Característica',
                    'value' => 'Valor',
                ],
                'columns' => [
                    'feature' => 'Característica',
                    'description' => 'Descripción',
                    'value' => 'Valor',
                ],
            ],
            'prices' => [
                'title' => 'Precios',
                'fields' => [
                    'price' => 'Precio',
                    'currency' => 'Moneda',
                    'billing_cycle' => 'Ciclo de Facturación',
                ],
                'columns' => [
                    'price' => 'Precio',
                    'currency' => 'Moneda',
                    'billing_cycle' => 'Ciclo de Facturación',
                ],
                'billing_cycles' => [
                    'monthly' => 'Mensual',
                    'quarterly' => 'Trimestral',
                    'yearly' => 'Anual',
                ],
            ],
        ],
    ],

    'feature' => [
        'navigation_label' => 'Características',
        'singular' => 'Característica',
        'plural' => 'Características',

        'fields' => [
            'name' => 'Nombre de la Característica',
            'slug' => 'Slug',
            'description' => 'Descripción',
            'default_value' => 'Valor Predeterminado',
            'active' => 'Activo',
        ],

        'placeholders' => [
            'name' => 'Acceso API',
            'slug' => 'acceso-api',
            'description' => 'Acceso a nuestra potente API con solicitudes ilimitadas',
            'default_value' => '0, falso, o vacío',
        ],

        'help' => [
            'slug' => 'Identificador único para la característica',
            'default_value' => 'Valor predeterminado cuando no está incluido en un plan',
            'active' => 'Si esta característica está disponible para asignación',
        ],

        'table' => [
            'default' => 'Predeterminado',
            'none' => 'Ninguno',
        ],

        'filters' => [
            'active_status' => 'Estado Activo',
        ],

        'actions' => [
            'new_feature' => 'Nueva Característica',
            'activate' => 'Activar',
            'deactivate' => 'Desactivar',
            'duplicate' => 'Duplicar',
        ],

        'bulk_actions' => [
            'activate_selected' => 'Activar Seleccionados',
            'deactivate_selected' => 'Desactivar Seleccionados',
        ],

        'notifications' => [
            'activated' => 'Característica activada',
            'deactivated' => 'Característica desactivada',
            'status_changed' => "La característica ':name' ha sido :status.",
            'duplicated' => 'Característica duplicada',
            'feature_duplicated' => "La nueva característica ':name' ha sido creada.",
        ],

        'messages' => [
            'slug_copied' => '¡Slug de la característica copiado!',
        ],

        'empty_state' => [
            'create_first' => 'Crea tu primera característica',
        ],

        'global_search' => [
            'status' => 'Estado',
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'default' => 'Predeterminado',
        ],
    ],

    'billing_cycle' => [
        'daily' => 'Diario',
        'weekly' => 'Semanal',
        'monthly' => 'Mensual',
        'yearly' => 'Anual',
    ],

    'usage' => [
        'navigation_label' => 'Uso de Características',
        'singular' => 'Uso',
        'plural' => 'Usos',
        'unlimited' => 'Ilimitado',

        'fields' => [
            'subscription' => 'Suscripción',
            'feature' => 'Característica',
            'used' => 'Usado',
            'limit' => 'Límite',
            'period_start' => 'Inicio del Período',
            'period_end' => 'Fin del Período',
            'usage_percentage' => 'Porcentaje de Uso',
            'status' => 'Estado',
            'project' => 'Proyecto',
            'additional_limit' => 'Límite Adicional',
        ],

        'status' => [
            'na' => 'N/A',
            'unlimited_na' => 'N/A (Ilimitado)',
            'unlimited' => 'Ilimitado',
            'over_limit' => 'Sobre el Límite',
            'near_limit' => 'Cerca del Límite',
            'within_limit' => 'Dentro del Límite',
        ],

        'table' => [
            'no_project' => 'Sin proyecto',
            'usage_percent' => 'Uso %',
            'na' => 'N/A',
        ],

        'filters' => [
            'feature' => 'Característica',
            'project' => 'Proyecto',
            'over_limit' => 'Sobre el Límite',
            'near_limit' => 'Cerca del Límite (80%+)',
            'unlimited' => 'Características Ilimitadas',
            'current_period' => 'Período Actual',
            'period_from' => 'Período Desde',
            'period_until' => 'Período Hasta',
        ],

        'actions' => [
            'reset_usage' => 'Reiniciar Uso',
            'extend_limit' => 'Extender Límite',
        ],

        'bulk_actions' => [
            'reset_usage' => 'Reiniciar Uso',
        ],

        'notifications' => [
            'usage_reset' => 'Uso reiniciado',
            'usage_reset_message' => "El uso de la característica ':feature' ha sido reiniciado a 0.",
            'limit_extended' => 'Límite extendido',
            'limit_extended_message' => 'Límite aumentado en :amount unidades.',
        ],

        'confirmations' => [
            'reset_usage' => '¿Estás seguro de que quieres reiniciar el contador de uso a 0?',
        ],

        'help' => [
            'additional_limit' => 'Cantidad a agregar al límite actual',
        ],

        'global_search' => [
            'project' => 'Proyecto',
            'used' => 'Usado',
            'usage' => 'Uso',
            'period' => 'Período',
            'na' => 'N/A',
        ],
    ],

    'coupon' => [
        'navigation_label' => 'Cupones',
        'singular' => 'Cupón',
        'plural' => 'Cupones',
        'copy_suffix' => 'COPIA',

        'fields' => [
            'code' => 'Código del Cupón',
            'discount_amount' => 'Cantidad de Descuento',
            'discount_type' => 'Tipo de Descuento',
            'expires_at' => 'Expira el',
            'usage_limit' => 'Límite de Uso',
            'used_count' => 'Veces Usado',
            'active' => 'Activo',
            'description' => 'Descripción',
        ],

        'discount_types' => [
            'fixed' => 'Cantidad Fija',
            'percentage' => 'Porcentaje',
        ],

        'placeholders' => [
            'code' => 'AHORRA20',
            'discount_amount' => '10.00 o 20',
            'expires_at' => 'Seleccionar fecha de expiración',
            'usage_limit' => 'Dejar vacío para ilimitado',
            'description' => 'Descripción opcional para uso interno',
        ],

        'table' => [
            'discount' => 'Descuento',
            'type' => 'Tipo',
            'unlimited' => 'Ilimitado',
            'used' => 'Usado',
            'expires' => 'Expira',
            'never' => 'Nunca',
        ],

        'filters' => [
            'discount_type' => 'Tipo de Descuento',
            'active_status' => 'Estado Activo',
            'expired' => 'Cupones Expirados',
            'expires_soon' => 'Expira en 7 días',
            'expires_after' => 'Expira Después',
            'expires_before' => 'Expira Antes',
        ],

        'actions' => [
            'new_coupon' => 'Nuevo Cupón',
            'activate' => 'Activar',
            'deactivate' => 'Desactivar',
            'duplicate' => 'Duplicar',
        ],

        'bulk_actions' => [
            'activate_selected' => 'Activar Seleccionados',
            'deactivate_selected' => 'Desactivar Seleccionados',
        ],

        'notifications' => [
            'activated' => 'Cupón activado',
            'deactivated' => 'Cupón desactivado',
            'status_changed' => "El cupón ':code' ha sido :status.",
            'duplicated' => 'Cupón duplicado',
            'coupon_duplicated' => "El nuevo cupón ':code' ha sido creado.",
        ],

        'messages' => [
            'code_copied' => '¡Código del cupón copiado!',
        ],

        'global_search' => [
            'type' => 'Tipo',
            'discount' => 'Descuento',
            'status' => 'Estado',
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'used' => 'Usado',
        ],

        'empty_state' => [
            'create_first' => 'Crea tu primer cupón',
        ],
    ],

    'subscription' => [
        'navigation_label' => 'Suscripciones',
        'singular' => 'Suscripción',
        'plural' => 'Suscripciones',

        'fields' => [
            'project' => 'Proyecto',
            'plan' => 'Plan',
            'plan_price' => 'Precio del Plan',
            'coupon' => 'Cupón',
            'start_date' => 'Fecha de Inicio',
            'next_billing_date' => 'Próxima Fecha de Facturación',
            'amount_due' => 'Cantidad Adeudada',
            'currency' => 'Moneda',
            'status' => 'Estado',
            'trial_ends_at' => 'La Prueba Termina el',
            'canceled_at' => 'Cancelado el',
            'cancellation_reason' => 'Razón de Cancelación',
            'price' => 'Precio',
            'billing' => 'Facturación',
            'trial_days' => 'Días de Prueba Adicionales',
        ],

        'status' => [
            'active' => 'Activo',
            'canceled' => 'Cancelado',
            'paused' => 'Pausado',
            'expired' => 'Expirado',
            'pending' => 'Pendiente',
            'trial' => 'Prueba',
        ],

        'table' => [
            'no_project' => 'Sin proyecto',
            'no_coupon' => 'Sin cupón',
            'started' => 'Iniciado',
            'next_billing' => 'Próxima Facturación',
            'trial_ends' => 'Fin de Prueba',
            'no_trial' => 'Sin prueba',
            'canceled' => 'Cancelado',
            'active' => 'Activo',
        ],

        'filters' => [
            'status' => 'Estado',
            'plan' => 'Plan',
            'project' => 'Proyecto',
            'billing_cycle' => 'Ciclo de Facturación',
            'with_coupon' => 'Con Cupón',
            'overdue_billing' => 'Facturación Vencida',
            'due_soon' => 'Vence en 7 días',
            'trial_ending' => 'Prueba Terminando Pronto',
            'created_after' => 'Creado Después',
            'created_before' => 'Creado Antes',
        ],

        'actions' => [
            'new_subscription' => 'Nueva Suscripción',
            'cancel' => 'Cancelar',
            'pause' => 'Pausar',
            'resume' => 'Reanudar',
            'extend_trial' => 'Extender Prueba',
        ],

        'bulk_actions' => [
            'pause_selected' => 'Pausar Seleccionados',
            'cancel_selected' => 'Cancelar Seleccionados',
        ],

        'notifications' => [
            'canceled' => 'Suscripción cancelada',
            'subscription_canceled' => 'La suscripción #:id ha sido cancelada.',
            'paused' => 'Suscripción pausada',
            'subscription_paused' => 'La suscripción #:id ha sido pausada.',
            'resumed' => 'Suscripción reanudada',
            'subscription_resumed' => 'La suscripción #:id ha sido reanudada.',
            'trial_extended' => 'Prueba extendida',
            'trial_extended_message' => 'Prueba extendida por :days días.',
        ],

        'placeholders' => [
            'cancellation_reason' => 'Por favor proporciona una razón para la cancelación',
            'bulk_cancellation_reason' => 'Por favor proporciona una razón para la cancelación masiva',
        ],

        'global_search' => [
            'title' => 'Suscripción #:id - :project',
            'no_plan' => 'Sin Plan',
        ],

        'empty_state' => [
            'create_first' => 'Crea tu primera suscripción',
        ],

        'relations' => [
            'feature_usages' => [
                'title' => 'Usos de Características',
                'unlimited' => 'Ilimitado',
                'columns' => [
                    'feature' => 'Característica',
                    'description' => 'Descripción',
                    'used' => 'Usado',
                    'limit' => 'Límite',
                    'remaining' => 'Restante',
                    'usage_percentage' => 'Uso %',
                    'overage_count' => 'Conteo de Excesos',
                    'period_start' => 'Inicio del Período',
                    'period_end' => 'Fin del Período',
                ],
                'filters' => [
                    'at_limit' => 'En el Límite',
                    'unlimited' => 'Características Ilimitadas',
                ],
                'actions' => [
                    'reset_usage' => 'Reiniciar Uso',
                    'view_all_features' => 'Ver Resumen de Todas las Características',
                ],
            ],
            'histories' => [
                'title' => 'Historial de Suscripciones',
                'columns' => [
                    'status' => 'Estado',
                    'plan' => 'Plan',
                    'price' => 'Precio',
                    'billing_cycle' => 'Ciclo de Facturación',
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