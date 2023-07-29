<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;

trait SupportsProjections
{
    public function newModel(array $attributes = [])
    {
        return Factory::newModel([
            'uuid' => fake()->uuid,
            ...$attributes
        ])->writeable();
    }
}
