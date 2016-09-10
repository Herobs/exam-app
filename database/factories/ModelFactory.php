<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Exam::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->numerify('测试考试 ###'),
        'start' => $faker->dateTimeBetween('-3 days', '+3 days'),
        'duration' => $faker->numberBetween(3600, 36000),
        'holder' => 1000,
        'type' => $faker->randomElement(['student', 'account', 'password']),
        'hidden' => 0
    ];
});
