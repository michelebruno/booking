<?php

namespace App\Traits;

use Illuminate\Support\Arr;

/**
 * 
 */
trait HaIndirizzo
{
    use HaAttributiMeta;

    private function _getPrefixedIndirizzo($prefix)
    {
        $indirizzo = [];
        
        $meta = $this->meta;
        
        if ( $meta = $this->getMeta( $prefix . "_" . 'via' ) ) {
            $indirizzo['via'] = $meta;
        }
        
        if ( $meta = $this->getMeta( $prefix . "_" . 'civico' ) ) {
            $indirizzo['civico'] = $meta;
        }
        
        if ( $meta = $this->getMeta( $prefix . "_" . 'citta' ) ) {
            $indirizzo['citta'] = $meta;
        }
        
        if ( $meta = $this->getMeta( $prefix . "_" . 'provincia' ) ) {
            $indirizzo['provincia'] = $meta;
        }
        
        if ( $meta = $this->getMeta( $prefix . "_" . 'cap' ) ) {
            $indirizzo['cap'] = $meta;
        }

        return $indirizzo;
    }

    private function _setPrefixedIndirizzo( string $prefix, array $indirizzo )
    {
        foreach ($indirizzo as $key => $value) {

            $chiave = $prefix . '_' . strtolower($key);

            if ( ( is_numeric($value) || is_string($value) ) && ! is_null($value) ) {
                $this->setMeta( $chiave, $value );
            } elseif ( $this->getMeta($chiave) ) {
                $this->deleteMeta($chiave);
            }
        }
    }

    public function getIndirizzoAttribute()
    {
        return $this->_getPrefixedIndirizzo('indirizzo');
    }
    
    public function setIndirizzoAttribute( array $indirizzo )
    {
        $this->_setPrefixedIndirizzo('indirizzo', $indirizzo);
    }

    public function getSedeLegaleAttribute()
    {
        return $this->_getPrefixedIndirizzo('sede_legale');
    }
    public function setSedeLegaleAttribute( array $indirizzo)
    {
        $this->_setPrefixedIndirizzo('sede_legale', $indirizzo);
    }
}
