<?php

namespace App\Models;

use App\Interfaces\Eventable;
use App\Traits\UuidHelpers;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\EventSourcing\Projections\Projection;

abstract class AuthenticatableModelProjection extends Projection implements Eventable, AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
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
