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
                $this->meta->where('chiave', $chiave)->delete();
            }
        }
    }

    public function getIndirizzoAttribute()
    {
        return $this->_getPrefixedIndirizzo('indirizzo_');
    }

    public function getSedeLegaleAttribute()
    {
        return $this->_getPrefixedIndirizzo('sede_legale_');
    }
}
