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

$factory->define(App\Invitation::class, function (Faker\Generator $faker) {
    return [
        'token' => str_random(24),
        'created_by_id' => Auth::user()->id,
        'email' => $faker->unique()->safeEmail,
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

/**
 * Project Factories
 */

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(2, true)
    ];
});

/**
 * Section Factories
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Section::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(2, true)
    ];
});

/**
 * Task Factories
 */
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(4, true),
        'note' => $faker->paragraph,
        'due_date' => $faker->date('Y-m-d'),
        'priority_id' => $faker->numberBetween(1,3),
        'status_id' => $faker->numberBetween(1,2),
        'created_by_id' => Auth::user()->id,
    ];
});
$factory->define(App\UserTask::class, function () {
    return [
    ];
});