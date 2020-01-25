<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class TransazionePaypal extends \App\Transazione
{
    protected $attributes = [
        'gateway' => 'paypal'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('gateway_paypal', function (Builder $builder)
        {
            return $builder->where('gateway', 'paypal');
        });
    }
    
}
