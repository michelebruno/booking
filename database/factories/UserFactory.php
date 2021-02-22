<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

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
$factory->define(User::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('password'), // password
        'ruolo' => $faker->randomElement([User::RUOLO_ADMIN, User::RUOLO_ACCOUNT])
    ];
});

$factory->define(\App\Fornitore::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        "nome" => $faker->name,
        'email_verified_at' => now(),
        "cf" => $faker->bothify('??????##?##?###?'),
        "piva" => $faker->numerify('0##########'),
        'password' => Hash::make('password'), // password
        'ruolo' => \App\Fornitore::RUOLO_FORNITORE,
        "SDI" => strtoupper($faker->bothify("##?#???")),
        'ragione_sociale' => $faker->company,
        "indirizzo" => [
            "via" => $faker->streetName,
            "citta" => $faker->city,
            "provincia" => $faker->countryCode,
            "cap" => $faker->postcode,
            "civico" => $faker->randomNumber(3)
        ],
        "sede_legale" => [
            "via" => $faker->streetName,
            "citta" => $faker->city,
            "provincia" => $faker->countryCode,
            "CAP" => $faker->postcode,
            "civico" => $faker->randomNumber(3)
        ]
    ];

});

