<?php

use Illuminate\Database\Seeder;

class TariffeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! \App\Tariffa::whereSlug("intero")->count()) {
            $intero = new \App\Tariffa();
            $intero->slug = "intero";
            $intero->nome = "Intero";
            $intero->save();
        }
        if (! \App\Tariffa::whereSlug("ridotto")->count()) {

            $ridotto = new \App\Tariffa();
            $ridotto->slug = "ridotto";
            $ridotto->nome = "Ridotto";
            $ridotto->save();
        }
        if (! \App\Tariffa::whereSlug("bambini")->count()) {
            $bambini = new \App\Tariffa();
            $bambini->slug = "bambini";
            $bambini->nome = "Bambini";
            $bambini->save();
        }
    }
}
