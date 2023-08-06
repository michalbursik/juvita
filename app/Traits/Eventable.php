<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait Eventable
{
    public function __construct(array $attributes = [])
    {
        $this->fillable = ['uuid', ... $this->fillable];

        parent::__construct($attributes);
    }

    public static function createWithAttributes(array $attributes): static
    {
        $attributes['uuid'] = (string) Uuid::uuid4();

        $event = static::getEventName('Created');

        event(new $event($attributes));

        return static::uuid($attributes['uuid']);
    }

    public function modify(array $attributes): static
    {
        $event = static::getEventName('Modified');

        event(new $event($this->uuid, $attributes));

        return static::uuid($this->uuid);
    }

    public function remove(): void
    {
        $event = static::getEventName('Removed');

        event(new $event($this->uuid));
    }

    public static function getEventName(string $eventType): string
    {
        return 'App\\Events\\'.class_basename(static::class).$eventType;
    }

    public static function uuid(string $uuid): ?static
    {
        return static::query()->where('uuid', $uuid)->first();
    }
}
