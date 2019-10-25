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

        factory(App\User::class, 10)
            ->create()
            ->each( function ($user) {
                $user->meta()->save(factory(App\Models\UserMeta::class)->make());
            });
        
    }
}
