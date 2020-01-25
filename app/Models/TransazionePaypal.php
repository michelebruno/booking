<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class TransazionePayPal extends \App\Transazione
{
    protected $attributes = [
        'gateway' => 'PayPal'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('gateway_paypal', function (Builder $builder)
        {
            return $builder->where('gateway', 'PayPal');
        });
    }
    
}
