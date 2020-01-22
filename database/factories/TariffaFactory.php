<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Tariffa;
use App\Models\VarianteTariffa;
use Faker\Generator as Faker;

$factory->define(Tariffa::class, function (Faker $faker) {
    $varianti = VarianteTariffa::where('slug', '<>', 'intero')->get(['id']);

    return [
        'importo' => $faker->numberBetween(8,200),
        'variante_tariffa_id' => $faker->randomElement($varianti)
    ];
});

$factory->define(Tariffa::class,  function (Faker $faker) {
    $tariffa = VarianteTariffa::slug('intero');
    return [
        'importo' => $faker->numberBetween(8,200),
        'variante_tariffa_id' => $tariffa->id
    ];
}, 'intero');
