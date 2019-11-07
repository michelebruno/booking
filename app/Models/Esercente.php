<?php

namespace App\Models;

use App\User; 

class Esercente extends User
{
    protected $attributes = [
        'ruolo' => "esercente",
    ];

    protected $fillable = [
        'username', 'email', 'password', 'cf', 'piva'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('ruolo_esercente', function (Builder $builder)
        {
            $builder->where('ruolo', 'esercente');
        });
    }
}
