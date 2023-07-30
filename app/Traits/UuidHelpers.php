<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait UuidHelpers
{
    public static function createWithAttributes(array $attributes): static
    {
        static::setModelEvents();

        $attributes['uuid'] = (string) Uuid::uuid4();

        $event = static::getCreateEvent();

        event(new $event($attributes));

        return static::uuid($attributes['uuid']);
    }
    public static function uuid(string $uuid): ?static
    {
        return static::query()->where('uuid', $uuid)->first();
    }
}
