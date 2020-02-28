<?php

use App\Fornitore;
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
        $admin->ruolo = User::RUOLO_ADMIN;
        $admin->save();
        $admin->markEmailAsVerified();

        $account_manager = new User();
        $account_manager->username = 'AC';
        $account_manager->nome = 'Account manager';
        $account_manager->email = 'acmanager@example.com';
        $account_manager->password = Hash::make('password'); 
        $account_manager->ruolo = User::RUOLO_ADMIN;
        $account_manager->save();
        $account_manager->markEmailAsVerified();

        $fornitore = new Fornitore();
        $fornitore->username = 'Fornitore';
        $fornitore->nome = 'Fornitore';
        $fornitore->email = 'fornitore@example.com';
        $fornitore->password = Hash::make('password'); 
        $fornitore->ruolo = 'fornitore';
        $fornitore->cf = 'CFFORNITORE2016L';
        $fornitore->piva = '12345678901';
        $fornitore->save();
        $fornitore->nome = "Fornitore prova";
        $fornitore->pec = "pec@pec.example.com";
        $fornitore->sdi = "000000";
        $fornitore->indirizzo = [
            "via" => "Via Marconi",
            "civico" => "24",
            "citta" => "Bologna",
            "cap" => "79865",
            "provincia" => "BO"
        ];
        $fornitore->save();
        $fornitore->markEmailAsVerified();

        $cliente = new User();
        $cliente->username = 'Cliente';
        $cliente->nome = 'Cliente';
        $cliente->email = 'cliente@example.com';
        $cliente->password = Hash::make('password'); 
        $cliente->ruolo = 'cliente';
        $cliente->save();
        $cliente->markEmailAsVerified();

        $manuel = new User([
            'username' => 'Manuel',
            'nome' => 'Manuel Perre',
            'email' => 'perre.manuel33@gmail.com',
            'password' => Hash::make("password"),
            'ruolo' => User::RUOLO_ADMIN,
        ]);
        $manuel->save();
        $manuel->markEmailAsVerified();

        $federica = new User([
            'username' => 'Federica',
            'nome' => 'Federica Mari',
            'email' => 'federicamari1994@gmail.com',
            'password' => Hash::make("password"),
            'ruolo' => User::RUOLO_ADMIN,
        ]);
        $federica->save();
        $federica->markEmailAsVerified();

        // factory(App\User::class, 10)
        //     ->create()
        //     ->each( function ($user) {
        //         $user->meta()->save(factory(App\UserMeta::class)->make());
        //     });
        
    }
}
