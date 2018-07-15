<?php

use Faker\Generator as Faker;
use MultiTenantLaravel\Tests\Models\User;
use MultiTenantLaravel\Tests\Models\Tenant;
use MultiTenantLaravel\Tests\Models\Dealership;

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

$factory->define(Tenant::class, function (Faker $faker) {

    //Generate a random number to help avoid duplicate slugs
    $unique = rand(100,999);

    $name = $faker->word.'-'.$unique;
    $user = factory(User::class)->create();

    return [
        'name' => $name,
        'owner_id' => $user->id,
        'slug' => str_slug($name),
    ];
});

$factory->define(Dealership::class, function (Faker $faker) {

    //Generate a random number to help avoid duplicate slugs
    $unique = rand(100,999);

    $name = $faker->word.'-'.$unique;
    $user = factory(User::class)->create();

    return [
        'name' => $name,
        'owner_id' => $user->id,
        'slug' => str_slug($name),
    ];
});
