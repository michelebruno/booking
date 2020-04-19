<?php

use Illuminate\Database\Seeder;

class FornitureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fornitori = \App\Fornitore::all();
        $tariffe = \App\Tariffa::all();

        factory(\App\Fornitura::class, 300)->create()->each(function (\App\Fornitura $fornitura) use ($fornitori, $tariffe) {
            $fornitura
                ->fornitore()
                ->associate($fornitori->random()->_id);

            $intero = factory(\App\Importo::class)
                    ->make()
                    ->tariffa()
                    ->associate($tariffe->firstWhere("slug", "intero"));


            $fornitura->tariffe()->associate($intero);
            $fornitura->save();

        });
    }
}
