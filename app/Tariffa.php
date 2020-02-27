<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tariffa
 *
 * @property int $id
 * @property int $prodotto_id
 * @property int $variante_tariffa_id
 * @property float $importo
 * @property mixed $imponibile
 * @property-read mixed $iva
 * @property-read mixed $nome
 * @property mixed $slug
 * @property-read \App\Prodotto $prodotto
 * @property-read \App\VarianteTariffa $variante
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereProdottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereVarianteTariffaId($value)
 * @mixin \Eloquent
 */
class Tariffa extends Model
{
    protected $table = "tariffe";

    protected $appends = [
        'imponibile', 'slug' , 'nome', 
    ];    

    public $fillable = [
        'variante_tariffa_id', 'importo', 'slug'
    ];

    /**
     * ! Deve essere nascosto, altrimenti toArray() non funziona!
     */
    protected $hidden = [
        'prodotto'
    ];

    public $timestamps = false;

    public function prodotto()
    {
        return $this->belongsTo( 'App\Prodotto', 'prodotto_id' )->withTrashed();
    }
    /**
     * è usato per ridurre le query.
     */
    public function getVarianteAttribute()
    {        
        return app('VariantiTariffe')->firstWhere('id', $this->variante_tariffa_id );
    }

    public function variante()
    {
        return $this->belongsTo('App\VarianteTariffa', 'variante_tariffa_id');
    }

    public function getSlugAttribute()
    {
        return $this->variante->slug;
    }

    public function setSlugAttribute( $slug )
    {
        $varianti = app('VariantiTariffe');

        if ( $variante = $varianti->has('slug', $slug )) {
            return $this->attributes['variante_tariffa_id'] = $variante->id;
        } else abort(422, 'La variante indicata non esiste.');

    }

    public function getNomeAttribute()
    {
        return $this->variante->nome;
    }

    public function getIvaAttribute()
    {
        return app('Prodotti')->find( $this->prodotto_id)->iva;
    }
    
    public function getImponibileAttribute()
    {        
        return round( $this->importo / ( 1 + $this->iva / 100 ) , 2 );
    }
    
    public function setImponibileAttribute( float $value )
    {
        return $this->attributes['importo'] = self::includiIva( $value , $this->iva );
    }

    public static function includiIva($importo, $iva)
    {
        return round( $importo * ( 1 + $iva / 100 ) , 2 );
    }

    public static function escludiIva($imponibile, $iva )
    {
        return round( $imponibile / ( 1 + $iva / 100 ) , 2 );
    }
}
