<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\EventSourcing\Projections\Projection;

/**
 * App\Models\AuthenticatableProjection
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AuthenticatableProjection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthenticatableProjection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthenticatableProjection query()
 * @mixin \Eloquent
 */
class AuthenticatableProjection extends Projection implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
}
