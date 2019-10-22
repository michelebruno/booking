<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'username' => 'Michele Bruno',
            'email' => 'bm.michelebruno@gmail.com',
            'cf' => 'BRNMHL97M08A285M',
            'password' => Hash::make('password'),
            'livello' => 'admin',
            'abilitato' => true,
            'created_at' => '2019-10-21 14:38:37',
            'updated_at' => '2019-10-21 14:38:37'
        ]);

        factory(App\User::class, 10)
            ->create()
            ->each( function ($user) {
                $user->meta()->save(factory(App\Models\UserMeta::class)->make());
            });
        
    }
}
