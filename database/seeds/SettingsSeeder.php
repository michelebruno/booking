<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $progr_ordini = Setting::progressivo('ordini', date('Y') );
        $progr_ordini->valore = 70;
        $progr_ordini->save();
    }
}
