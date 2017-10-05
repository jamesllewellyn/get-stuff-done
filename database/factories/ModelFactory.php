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

/**
 * User Factories
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'handle' =>  $faker->word,
        'password' => $password ?: $password = bcrypt($faker->password(7)),
        'remember_token' => str_random(10),
    ];
});

/**
 * Team Factories
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Team::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(2, true)
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\UserTeam::class, function () {
    return [
    ];
});

