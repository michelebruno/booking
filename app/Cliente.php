<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class Cliente extends User
{
    protected $attributes = [
        "ruolo" => self::RUOLO_CLIENTE
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('ruolo_cliente', function (Builder $builder)
        {
            return $builder->where( "ruolo", self::RUOLO_CLIENTE );
        });
    }

    public function ordini()
    {
        return $this->hasMany('App\Ordine', 'cliente_id');
    }

    public function getLinksAttribute()
    {
        return [
            'self' => '/clienti/' . $this->id,
            'forceDelete' => '/clienti/' . $this->id . '/forceDelete'
        ];
    }
}
