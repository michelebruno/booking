<?php
 
namespace App;

use App\VarianteTariffa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;


/**
 * App\Prodotto
 *
 * @property int $id
 * @property string $titolo
 * @property string $codice
 * @property string $tipo
 * @property string|null $descrizione
 * @property int|null $esercente_id
 * @property string $stato
 * @property int|null $disponibili
 * @property int $iva
 * @property int|null $wp
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read array $links
 * @property-read string $smart
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tariffa[] $tariffe
 * @property-read int|null $tariffe_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto attivi()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereCodice($codice)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Prodotto onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereEsercenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereWp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Prodotto withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Prodotto withoutTrashed()
 * @mixin \Eloquent
 */
class Prodotto extends Model
{
    use SoftDeletes;

    const TIPO_DEAL = "deal";

    const TIPO_SERVIZIO = "servizio";
    
    protected $table = "prodotti";

    protected $hidden = [
        'esercente_id', 'deleted_at', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'condensato', 'cestinato' , '_links' 
    ];

    public function getRouteKeyName()
    {
        return 'codice';
    }

    public function tariffe()
    {
        return $this->hasMany( 'App\Tariffa', 'prodotto_id' );
    }

    /**
     * 
     * @param  int  $quantità La quantità di cui ridurre la disponibiltà del prodotto.
     */
    public function riduciDisponibili(int $quantità, bool $salva = true )
    {
        // TODO controllare che ce ne siano abbastanza...
        $this->disponibili -= $quantità;

        if ($salva) {
            $this->save();
        }
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

    public function setCodiceAttribute( $value )
    {
        $value = preg_replace( '/\s+/', '-', $value );
        return $this->attributes[ 'codice' ] = strtoupper( $value );
    }

    public function setTariffeAttribute( $tariffe )
    {
        if (! $tariffe ) return;

        foreach ( $tariffe as $key => $value ) { 

            $etichetta = app('VariantiTariffe')[$key];

            $this->tariffe()->updateOrCreate( [ 'variante_tariffa_id' => $etichetta->id ] , $value );

        }
    }

    /**
     * getSmartAttribute
     * @todo Scegliere come compilarlo.
     * @todo Aggiungere agli append.
     * @return string
     */
    public function getSmartAttribute()
    {
        # code...
    }

    public function getCondensatoAttribute()
    {
        $intero = $this->tariffe->firstWhere('slug', 'intero' );
        
        $euro = $intero ? " | " . " €" . $intero->imponibile : '' ;

        return $this->codice . " - " . $this->titolo . $euro ;
    }

    public function getCestinatoAttribute()
    {
        return $this->trashed();
    }

    /* 
     *
     * * SCOPES *
     * 
     */

    public function scopeDisponibili($query, $more_than = 0)
    {
        return $query->where('disponibili', '>', $more_than);
    }

    public function scopeAttivi($query)
    {
        // TODO usare le costanti di classe.
        return $query->whereIn('stato' , [ 'pubblico' , 'privato' ] );
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array["tariffe"] = $this->tariffe->keyBy('slug');

        return $array;
    }
}
