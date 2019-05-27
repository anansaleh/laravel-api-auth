<?php

use Faker\Generator as Faker;
use App\User;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Transaction::class, function (Faker $faker) {
    $user = App\User::first();
    $user_id = ($user)? $user->user_id : null;
    return [
        'amount' => $faker->randomFloat(2, 10, 1000 ),
        'created_by' => $user_id,
        'updated_by' => $user_id
    ];
    // run tinker
    // factory(\App\Transaction::class, 50)->create();
});