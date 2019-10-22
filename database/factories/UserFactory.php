<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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
    $data = [
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'cf' => $faker->unique()->numerify('0##########'),
        'livello' => $faker->randomElement(['admin', 'fornitore', 'cliente'])
    ];
    
    switch ($data['livello']) {
        case 'fornitore':
            $data['piva'] = $faker->numerify('###########');
            break;
    }

    return $data;

});

$factory->define(App\Models\UserMeta::class, function (Faker $faker)
{
    return [
        'chiave' => $faker->randomElement(['indirizzo', 'pec', 'wow']),
        'valore' => $faker->text()
    ];

});