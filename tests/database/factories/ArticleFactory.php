<?php

use Faker\Generator as Faker;
use Qirolab\Tests\Laravel\Reactions\Stubs\Models\Article;

/*
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
    ];
});
