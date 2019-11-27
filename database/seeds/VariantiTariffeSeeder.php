<?php

use App\Models\VarianteTariffa;
use Illuminate\Database\Seeder;

class VariantiTariffeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intero = new VarianteTariffa([ 'nome' => 'Intero' , 'slug' => 'intero' ]) ;
        $intero->save();

        $ridotto = new VarianteTariffa([ 'nome' => 'Ridotto' , 'slug' => 'ridotto' ]) ;
        $ridotto->save();

    }
}
