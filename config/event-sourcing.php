<?php

return [
    'queue' => null,

    'stored_event_model' => Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent::class,

    'stored_event_repository' => Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository::class,

    'stored_snapshots_model' => Spatie\EventSourcing\StoredEvents\Models\EloquentStoredSnapshot::class,

    'consistency_check_enabled' => env('EVENT_SOURCING_CONSISTENCY_CHECK_ENABLED', false),

    'event_class_map' => [],

    'projectors' => [
        App\Domain\Warehouse\Projections\WarehouseProjector::class,
    ],

    'reactors' => [
        // Reactors can send notifications, integrate, etc.
    ],

    'aggregate_caches' => [
        // You can configure aggregate caches here if needed
    ],
];
