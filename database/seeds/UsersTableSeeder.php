<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User();
        $admin->username = 'Michele Bruno';
        $admin->email = 'bm.michelebruno@gmail.com';
        $admin->password = Hash::make('password'); 
        $admin->livello = 'admin';
        $admin->api_token = 'secret_token';
        $admin->save();

        $account_manager = new User();
        $account_manager->username = 'AC';
        $account_manager->email = 'acmanager@example.com';
        $account_manager->password = Hash::make('password'); 
        $account_manager->livello = 'account_manager';
        $account_manager->api_token = 'ac_secret_token';
        $account_manager->save();

        $esercente = new User();
        $esercente->username = 'Esercente';
        $esercente->email = 'esercente@example.com';
        $esercente->password = Hash::make('password'); 
        $esercente->livello = 'esercente';
        $esercente->api_token = 'esercente_secret_token';
        $esercente->save();

        $cliente = new User();
        $cliente->username = 'Cliente';
        $cliente->email = 'cliente@example.com';
        $cliente->password = Hash::make('password'); 
        $cliente->livello = 'cliente';
        $cliente->api_token = 'cliente_secret_token';
        $cliente->save();

        factory(App\User::class, 10)
            ->create()
            ->each( function ($user) {
                $user->meta()->save(factory(App\Models\UserMeta::class)->make());
            });
        
    }
}
