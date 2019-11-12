<?php

namespace App\Traits;

use Illuminate\Support\Arr;

/**
 * 
 */
trait HaIndirizzo
{
    public function setIndirizzoAttribute($indirizzo)
    {
        if ( Arr::exists( $indirizzo, 'via')) $this->meta()->updateOrCreate(['chiave' => 'indirizzo_via'], ['user_id' => $this->id, 'valore' => $indirizzo['via']]);
        if ( Arr::exists( $indirizzo, 'civico')) $this->meta()->updateOrCreate(['chiave' => 'indirizzo_civico'], ['user_id' => $this->id, 'valore' => $indirizzo['civico']]);
        if ( Arr::exists( $indirizzo, 'città')) $this->meta()->updateOrCreate(['chiave' => 'indirizzo_città'], ['user_id' => $this->id, 'valore' => $indirizzo['città']]);
        if ( Arr::exists( $indirizzo, 'provincia')) $this->meta()->updateOrCreate(['chiave' => 'indirizzo_provincia'], ['user_id' => $this->id, 'valore' => $indirizzo['provincia']]);
        if ( Arr::exists( $indirizzo, 'CAP')) $this->meta()->updateOrCreate(['chiave' => 'indirizzo_cap'], ['user_id' => $this->id, 'valore' => $indirizzo['CAP']]);
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
        
        if ( Arr::exists( $meta , 'indirizzo_città' ) ) {
            $indirizzo['città'] = $meta['indirizzo_città'];
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
