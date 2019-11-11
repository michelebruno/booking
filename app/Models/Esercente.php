<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;

class Esercente extends User
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'cf', 'piva'
    ];

    protected $attributes = [
        'ruolo' => 'esercente'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('ruolo_esercente', function (Builder $builder)
        {
            return $builder->where('ruolo', 'esercente');
        });
    }
}
