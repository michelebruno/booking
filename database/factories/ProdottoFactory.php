<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Prodotto;
use Faker\Generator as Faker;

$factory->define(\App\Deal::class, function (Faker $faker) {
    return [
        "titolo" => $faker->title,
        "descrizione" => $faker->paragraphs,
        "disponibili" => $faker->randomNumber(2)
    ];
});

$factory->define(\App\Fornitura::class, function (Faker $faker) {
    $fornitori = \App\Fornitore::query()->limit(5)->get();
    return [
        "titolo" => $faker->title,
        "descrizione" => $faker->paragraphs,
        "disponibili" => $faker->randomNumber(2),
    ];
});
