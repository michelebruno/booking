<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Esercente;
use App\Models\Servizio;
use Faker\Generator as Faker;

$factory->define(Servizio::class, function (Faker $faker) {
    $esercenti = Esercente::all(['id']);
    return [
        'codice' => $faker->text(10),
        'titolo' => $faker->text(15),
        'descrizione' => $faker->text(20),
        'stato' => 'pubblico',
        'esercente_id' => $faker->randomElement($esercenti),
        'iva' => 22
    ];
});
