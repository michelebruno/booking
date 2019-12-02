<?php

use App\Models\Servizio;
use App\Models\Tariffa;
use App\Models\VarianteTariffa;
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

        factory(Servizio::class, 5)
            ->create()
            ->each( function ( $servizio )
            {
                $servizio->tariffe()->save( factory( Tariffa::class )->make() ) ;
            });
    }
}
