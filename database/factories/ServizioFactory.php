<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Esercente;
use App\Servizio;
use Faker\Generator as Faker;
$servizio_counter = 0;
$factory->define(Servizio::class, function (Faker $faker)  {
    global $servizio_counter;
    
    $esercenti = Esercente::all(['id']);
    $servizio_counter += 1;
    $es = $faker->randomElement($esercenti);
    return [
        'codice' => 'S-'. $es->id . "-" . $servizio_counter,
        'titolo' => $faker->text(15),
        'descrizione' => $faker->text(20),
        'stato' => 'pubblico',
        'esercente_id' => $es,
        'iva' => 22
    ];
});
