<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(\App\Models\Signature::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'message' => $faker->text(200),
        'user_id' => User::all()->random()->id,
    ];
});