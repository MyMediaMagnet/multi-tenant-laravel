<?php

use Faker\Generator as Faker;
use MultiTenantLaravel\Tests\Models\Permission;

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

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'label' => $faker->unique()->safeEmail,
    ];
});
