<?php
 
namespace App;

use App\VarianteTariffa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;


class Prodotto extends Model
{
    use SoftDeletes;

    protected $table = "prodotti";

    protected $hidden = [
        'esercente_id', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'condensato', 'cestinato' , 'tariffe' , '_links' 
    ];

    public function getRouteKeyName()
    {
        return 'codice';
    }

    /* 
     *  
     * * MUTATORS *
     * 
     */
    /**
     * 
     * @abstract
     * @return array
     */
    public function getLinksAttribute()
    {
        return [];
    }

    public function setCodiceAttribute($value)
    {
        $value = preg_replace('/\s+/', '-', $value);
        return $this->attributes['codice'] = strtoupper($value);
    }

    public function tariffe()
    {
        return $this->hasMany('App\Tariffa', 'prodotto_id');
    }

    public function getTariffeAttribute()
    {
        $tariffe = $this->tariffe()->get();

        $attribute = [];

        foreach ($tariffe as $t ) {
            $attribute[$t->slug] = $t;
        };

        return $attribute;
    }

    public function setTariffeAttribute($tariffe)
    {
        if (! $tariffe ) return;

        $etichette = VarianteTariffa::all()->toArray();

        foreach ($tariffe as $key => $value) { 

            $etichetta = VarianteTariffa::slug($key);

            $this->tariffe()->updateOrCreate([ 'variante_tariffa_id' => $etichetta->id ], $value );

        }
    }

    /**
     * getSmartAttribute
     * @todo Scegliere come compilarlo.
     * @todo Aggiungere agli append.
     * @return void
     */
    public function getSmartAttribute()
    {
        # code...
    }

    public function getCondensatoAttribute()
    {
        $euro = Arr::exists( $this->tariffe, 'intero' ) ? " | " . " €" . $this->tariffe['intero']->imponibile : '' ;

        return $this->codice . " - " . $this->titolo . $euro ;
    }

    public function getCestinatoAttribute()
    {
        return $this->trashed();
    }

    /* 
     *
     * SCOPES
     * 
     */

    public function scopeCodice($query, $codice)
    {
        return $query->where('codice', $codice)->firstOrFail();
    }

    public function scopeDisponibili($query, $more_than = 0)
    {
        return $query->where('disponibili', '>', $more_than);
    }

    public function scopeAttivi($query)
    {
        return $query->whereIn('stato' , [ 'pubblico' , 'privato' ]);
    }
}
