<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\User::whereEmail(config('booking.webmaster.email'))->count()) {

            factory(\App\User::class)->create([
                "email" => config("booking.webmaster.email"),
                "username" => "Michele Bruno",
                "ruolo" => \App\User::RUOLO_ADMIN,
                "password" => Hash::make("password")
            ])->each(function (\App\User $admin) { $admin->markEmailAsVerified(); });
        }

        factory(\App\User::class, 3)->create();

        factory(\App\Fornitore::class, 15)->create();
    }
}
