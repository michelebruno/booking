<?php

namespace App\Traits;

use Illuminate\Support\Arr;

/**
 * 
 */
trait HaIndirizzo
{
    public function setIndirizzoAttribute( array $indirizzo)
    {
        foreach ($indirizzo as $key => $value) {

            $chiave = 'indirizzo_' . strtolower($key);
            if ( $value && is_string($value) && strlen($value) ) {
                $this->meta()->updateOrCreate([ 'chiave' => $chiave ] , [ 'user_id' => $this->id , 'valore' => $value ]);
            } elseif ( Arr::exists( $this->meta, $chiave ) ) {
                $this->meta()->where('chiave', $chiave)->delete();
            }
        }
    }

    public function getIndirizzoAttribute()
    {
        $indirizzo = [];
        $meta = $this->meta;
        
        if ( Arr::exists( $this->meta , 'indirizzo_via' ) ) {
            $indirizzo['via'] = $this->meta['indirizzo_via'];
        }
        
        if ( Arr::exists( $meta , 'indirizzo_civico' ) ) {
            $indirizzo['civico'] = $meta['indirizzo_civico'];
        }
        
        if ( Arr::exists( $meta , 'indirizzo_citta' ) ) {
            $indirizzo['citta'] = $meta['indirizzo_citta'];
        }
        
        if ( Arr::exists( $meta , 'indirizzo_provincia' ) ) {
            $indirizzo['provincia'] = $meta['indirizzo_provincia'];
        }
        
        if ( Arr::exists( $meta , 'indirizzo_cap' ) ) {
            $indirizzo['cap'] = $meta['indirizzo_cap'];
        }

        return $indirizzo;
    }
}
