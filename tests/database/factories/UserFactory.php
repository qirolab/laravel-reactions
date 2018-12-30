<?php

use Faker\Generator as Faker;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\User;

/*
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
