<?php

use App\Deal;
use App\Fornitura;
use App\Tariffa;
use App\VarianteTariffa;
use Illuminate\Database\Seeder;

class ProdottiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variante = new VarianteTariffa();
        $variante->slug = 'intero';
        $variante->nome = 'Intero';
        $variante->save();

        $ridotto = new VarianteTariffa();
        $ridotto->slug = 'ridotto';
        $ridotto->nome = 'Ridotto';
        $ridotto->save();

        $deal = new Deal(['titolo' => 'Deal di prova' , 'codice' => 'D-Prova', 'stato' => 'pubblico' , 'iva' => '22' , 'disponibili' => 15 ]);
        $deal->save();

        $deal->tariffe()->create([
            'importo' => 70,
            'variante_tariffa_id' => $variante->id
        ]);

        $deal->tariffe()->create([
            'importo' => 60,
            'variante_tariffa_id' => $ridotto->id
        ]);

        factory(Fornitura::class, 3)
            ->create()
            ->each( function ( $fornitura ) use ( $deal )
            {
                $fornitura->tariffe()->save( factory( Tariffa::class , 'intero' )->make() ) ;
                $fornitura->tariffe()->save( factory( Tariffa::class )->make() ) ;
                $deal->forniture()->attach($fornitura);
            });

        

        $deal = new Deal(['titolo' => 'Deal secondo' , 'codice' => 'D-2', 'stato' => 'pubblico' , 'iva' => '22' , 'disponibili' => 18 ]);
        $deal->save();

        $deal->tariffe()->create([
            'importo' => 15,
            'variante_tariffa_id' => $variante->id
        ]);

        $deal->tariffe()->create([
            'importo' => 10,
            'variante_tariffa_id' => $ridotto->id
        ]);

        factory(Fornitura::class, 3)
            ->create()
            ->each( function ( $fornitura ) use ( $deal )
            {
                $fornitura->tariffe()->save( factory( Tariffa::class , 'intero' )->make() ) ;
                $fornitura->tariffe()->save( factory( Tariffa::class )->make() ) ;
                $deal->forniture()->attach($fornitura);
            });
    }
}
