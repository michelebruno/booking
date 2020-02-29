<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'chiave', 'valore', 'autoload'
    ];

    protected $attributes = [
        'autoload' => false
    ];

    protected $primaryKey = "chiave";

    public $incrementing = false;
    
    /**
     * 
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $true
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public function scopeAutoloaded($query, bool $true = true)
    {
        return $query->where('autoload', $true );
    }

    /**
     * Ritrova il progressivo nel formato _progressivo_ . join( "_" , $args )
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public function scopeProgressivo($query, ...$args)
    {        
        $scope = join("_", $args);

        /**
         * TODO ->lockForUpdate()
         */
        return $query->firstOrCreate( [ 'chiave' => '_progressivo_' . $scope ] , [ 'valore' => 1 , 'autoload' => false] );
    }

    /**
     * Funzione per i progressivi che potrebbero esssere sovrascritti dagli utenti.
     *
     * @param  Builder $query
     * @param  Closure|array $mustBeFalseFunction
     * @param  mixed $args
     *
     * @return void
     */
    public function scopeProgressivoSicuro( Builder $query , $DBprefix, array $mustHaveNoRes ,  ...$args)
    {
        $prefix = join('-', $args ) . '-';

        $retr = Setting::progressivo($DBprefix, ...$args);

        while ( ( call_user_func( $mustHaveNoRes,  $prefix . $retr->valore ) )->count() ) {            
            $retr->increment('valore');
        }

        return $prefix . $retr->valore;
    }
}
