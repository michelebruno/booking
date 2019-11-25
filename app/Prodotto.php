<?php
 
namespace App;

use App\Models\VarianteTariffa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Prodotto extends Model
{
    use SoftDeletes;

    protected $table = "prodotti";

    protected $hidden = [
        'esercente_id', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'tariffe'
    ];

    public function tariffe()
    {
        return $this->hasMany('App\Models\Tariffa', 'prodotto_id');
    }

    public function getTariffeAttribute()
    {
        $tariffe = $this->tariffe()->get();

        $a = [];

        foreach ($tariffe as $t ) {
            $a[$t->slug] = $t;
        };

        return $a;
    }

    public function setTariffeAttribute($tariffe)
    {
        $etichette = VarianteTariffa::all()->toArray();

        $etichette_slugs = array_column($etichette, 'slug');

        foreach ($tariffe as $key => $value) {

            $pos = array_search( $key, $etichette_slugs ) ;

            if ( $pos !== -1 ) {
                $this->tariffe()->updateOrCreate([ 'variante_tariffa_id' => $etichette[$pos]['id']], $value );
            }
        }
    }
}
