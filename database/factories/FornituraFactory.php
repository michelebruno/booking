<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Fornitore;
use App\Fornitura;
use Faker\Generator as Faker;

// TODO usare Setting::progressivo("fornitori", $es, "forniture"); e poi aumentare
$servizio_counter = 0;

$factory->define(Fornitura::class, function (Faker $faker)  {
    global $servizio_counter;
    
    $esercenti = Fornitore::all(['id']);
    $servizio_counter++;
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
