<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Builder;

class Cliente extends User
{
    protected $table = 'users';

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('ruolo_cliente', function (Builder $builder)
        {
            return $builder->where('ruolo', 'cliente');
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
