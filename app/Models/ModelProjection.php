<?php

namespace App\Models;

use App\Interfaces\Eventable;
use App\Traits\UuidHelpers;
use Spatie\EventSourcing\Projections\Projection;

abstract class ModelProjection extends Projection implements Eventable
{
    use UuidHelpers;

    public static array $modelEvents;

    public function __construct(array $attributes = [])
    {
        $this->fillable = ['uuid', ... $this->fillable];

        parent::__construct($attributes);
    }

    protected static function setCreateEvent(string $event): void
    {
        static::$modelEvents['create'] = $event;
    }
    protected static function getCreateEvent(): string
    {
        return static::$modelEvents['create'];
    }

    protected static function getUpdateEvent(): string
    {
        return static::$modelEvents['update'];
    }

    protected static function setUpdateEvent(string $className): void
    {
        static::$modelEvents['update'] = $className;
    }
}
