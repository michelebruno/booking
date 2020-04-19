<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Deal::class, function (Faker $faker) {
    return [
        "titolo" => rtrim($faker->sentence, '\.'),
        "descrizione" => $faker->paragraphs,
        "disponibili" => $faker->randomNumber(2)
    ];
});

$factory->define(\App\Fornitura::class, function (Faker $faker) {
    $fornitori = \App\Fornitore::query()->limit(5)->get();
    return [
        "titolo" => rtrim($faker->sentence, '\.'),
        "descrizione" => $faker->paragraphs,
        "disponibili" => $faker->randomNumber(2),
    ];
});

$factory->define(\App\Importo::class, function (Faker $faker) {
    return [
        "importo" => $faker->randomFloat(2, 0.3, 1000)
    ];
});
