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
        'tariffe' , '_links' , 'cestinato'
    ];

    public function setCodiceAttribute($value)
    {
        return $this->attributes['codice'] = strtoupper($value);
    }

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

    public function getCestinatoAttribute()
    {
        return $this->trashed();
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
