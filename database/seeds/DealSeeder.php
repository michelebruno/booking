<?php

use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forniture = \App\Fornitura::all();
        $tariffe = \App\Tariffa::all();

        factory(\App\Deal::class, 100)->make()->each(function (\App\Deal $deal) use ($forniture, $tariffe) {

            $intero = factory(\App\Importo::class)
                ->make()
                ->tariffa()
                ->associate($tariffe->firstWhere("slug", "intero"));

            $deal->tariffe()->associate($intero);

            $altratariffa = factory(\App\Importo::class)
                ->make()
                ->tariffa()
                ->associate($tariffe->whereNotIn("slug", ["intero"])->random());

            $deal->tariffe()->associate($altratariffa);

            $deal->save();
            $deal->forniture()->attach($forniture->random(mt_rand(3 , 10)));


        });
    }
}
