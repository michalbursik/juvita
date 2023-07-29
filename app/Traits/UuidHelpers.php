<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait UuidHelpers
{
    // TODO move to generic model (extend Projection)

    public static array $modelEvents;

    public function __construct(array $attributes = [])
    {
        $this->fillable = ['uuid', ... $this->fillable];

        parent::__construct($attributes);
    }

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

    private static function setCreateEvent(string $event): void
    {
        static::$modelEvents['create'] = $event;
    }
    private static function getCreateEvent(): string
    {
        return static::$modelEvents['create'];
    }

    private static function getUpdateEvent(): string
    {
        return static::$modelEvents['update'];
    }

    private static function setUpdateEvent(string $className): void
    {
        static::$modelEvents['update'] = $className;
    }
}
