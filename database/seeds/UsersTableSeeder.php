<?php

use App\Models\Esercente;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $admin->nome = 'Michele Bruno';
        $admin->email = 'bm.michelebruno@gmail.com';
        $admin->password = Hash::make('password'); 
        $admin->ruolo = 'admin';
        $admin->api_token = 'secret_token';
        $admin->save();
        $admin->markEmailAsVerified();

        $account_manager = new User();
        $account_manager->username = 'AC';
        $account_manager->nome = 'Account manager';
        $account_manager->email = 'acmanager@example.com';
        $account_manager->password = Hash::make('password'); 
        $account_manager->ruolo = 'account_manager';
        $account_manager->api_token = 'ac_secret_token';
        $account_manager->save();
        $account_manager->markEmailAsVerified();

        $esercente = new Esercente();
        $esercente->username = 'Esercente';
        $esercente->nome = 'Esercente';
        $esercente->email = 'esercente@example.com';
        $esercente->password = Hash::make('password'); 
        $esercente->ruolo = 'esercente';
        $esercente->cf = 'CFESERCENTE2016L';
        $esercente->piva = '12345678901';
        $esercente->api_token = 'esercente_secret_token';
        $esercente->save();
        $esercente->nome = "Esercente prova";
        $esercente->pec = "pec@pec.example.com";
        $esercente->sdi = "000000";
        $esercente->indirizzo = [
            "via" => "Via Marconi",
            "civico" => "24",
            "citta" => "Bologna",
            "cap" => "79865",
            "provincia" => "BO"
        ];
        $esercente->save();
        $esercente->markEmailAsVerified();

        $cliente = new User();
        $cliente->username = 'Cliente';
        $cliente->nome = 'Cliente';
        $cliente->email = 'cliente@example.com';
        $cliente->password = Hash::make('password'); 
        $cliente->ruolo = 'cliente';
        $cliente->api_token = 'cliente_secret_token';
        $cliente->save();
        $cliente->markEmailAsVerified();

        $manuel = new User([
            'username' => 'Manuel',
            'nome' => 'Manuel Perre',
            'email' => 'perre.manuel33@gmail.com',
            'password' => Hash::make("password"),
            'ruolo' => 'admin',
            'api_token' => Str::random(20)
        ]);
        $manuel->save();
        $manuel->markEmailAsVerified();

        $federica = new User([
            'username' => 'Federica',
            'nome' => 'Federica Mari',
            'email' => 'federicamari1994@gmail.com',
            'password' => Hash::make("password"),
            'ruolo' => 'admin',
            'api_token' => Str::random(20)
        ]);
        $federica->save();
        $federica->markEmailAsVerified();

        // factory(App\User::class, 10)
        //     ->create()
        //     ->each( function ($user) {
        //         $user->meta()->save(factory(App\Models\UserMeta::class)->make());
        //     });
        
    }
}
