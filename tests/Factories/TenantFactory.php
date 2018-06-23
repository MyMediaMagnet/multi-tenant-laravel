<?php

use Faker\Generator as Faker;
use MultiTenantLaravel\Tests\Models\User;
use MultiTenantLaravel\Tests\Models\Tenant;

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
    $name = $faker->name;
    $user = factory(User::class)->create();

    return [
        'name' => $name,
        'owner_id' => $user->id,
        'slug' => str_slug($name),
    ];
});
