<?php

namespace App;

use App\VoceOrdine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property string $stato può essere:
 *      - APERTO
 *      - SCADUTO
 *      - CHIUSO
 */
class Ticket extends Model
{
    protected $table = "tickets";

    protected $primaryKey = 'token';

    public $keyType = "string";

    public $retryAfter = 3;

    public $incrementing = false;

    protected $guarded = [
        "token"
    ];
    
    const APERTO = "APERTO";
    const SCADUTO = "SCADUTO";
    const CHIUSO = "CHIUSO";

    public static function boot()
    {
        parent::boot();

        /**
         * Imposta il token unico.
         */

        self::creating(function($ticket)
        {
            $ticket->token = self::generaToken();
        });

    }

    public function voce()
    {
        return $this->belongsTo(VoceOrdine::class);
    }

    public function ordine()
    {
        return $this->hasOneThrough(Ordine::class, VoceOrdine::class);
    }

    public function varianteTariffa()
    {
        return $this->belongsTo(VarianteTariffa::class, 'variante_tariffa_id');
    }

    /**
     * @param int $lenght La lunghezza del token.
     * @return string Un token unico di 10 lettere maiuscole.
     */
    public static function generaToken(int $lenght = 5)
    {

        $token = self::_token();

        while ( Ticket::find($token) ) {
            $token = self::_token($lenght);
        }

        return $token;
    }

    protected static function _token(int $lenght = 10)
    {
        $alfabenumerici = [ "A", "B", "C", "D", "E", "F", "G", "H", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "Z", "2", "3", "4", "5", "6", "7", "8", "9" ];
        
        $set = Arr::random( $alfabenumerici, $lenght );

        $token = "";

        for ($i=0; $i < $lenght; $i++) { 
            $token .= Arr::random($set, 1)[0];
        }

        return $token;
    }
}
