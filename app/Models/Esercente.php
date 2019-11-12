<?php

namespace App\Models;

use App\Traits\HaIndirizzo;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Esercente extends User
{
    use HaIndirizzo;
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

    public function toArray()
    {
        $array = parent::toArray();

        $array['nome'] = $this->nome;

        $array['pec'] = $this->pec;

        $array['sdi'] = $this->sdi;

        $array['indirizzo'] = $this->indirizzo;

        $array['sede_legale'] = $this->sede_legale;     

        $array['ragione_sociale'] = $this->ragione_sociale;

        $meta = $this->exceptFromTraits(self::class);

        $array['meta'] = Arr::except($meta, [ 'nome' , 'sdi', 'pec' ]); 

        return $array;
    }

    public function getNomeAttribute()
    {
        return Arr::exists($this->meta, 'nome') ? $this->meta['nome'] : null;
    }

    public function setNomeAttribute($value)
    {        
       if ($value) $this->meta()->updateOrCreate(['chiave' => 'nome'], [ 'user_id' => $this->id, 'valore' => $value ]);
    }

    public function getRagioneSocialeAttribute()
    {
        return Arr::exists($this->meta, 'ragione_sociale') ? $this->meta['ragione_sociale'] : null;
    }

    public function setRagioneSocialeAttribute($value)
    {
        if ($value) $this->meta()->updateOrCreate(['chiave' => 'ragione_sociale'], [ 'user_id' => $this->id, 'valore' => $value ]);
    }

    public function getSedeLegaleAttribute()
    {
        return Arr::exists($this->meta, 'sede_legale') ? $this->meta['sede_legale'] : null;
    }

    public function setSedeLegaleAttribute($value)
    {        
        if ($value) $this->meta()->updateOrCreate(['chiave' => 'sede_legale'], [ 'user_id' => $this->id, 'valore' => $value ]);
    }

    public function getPecAttribute()
    {
        return Arr::exists($this->meta, 'pec') ? $this->meta['pec'] : null;
    }

    public function setPecAttribute($value)
    {        
        if ($value) $this->meta()->updateOrCreate(['chiave' => 'pec'], [ 'user_id' => $this->id, 'valore' => $value ]);
    }

    public function getSDIAttribute()
    {
        return Arr::exists($this->meta, 'sdi') ? $this->meta['sdi'] : null;
    }

    public function setSDIAttribute($value)
    {        
       if ($value) $this->meta()->updateOrCreate(['chiave' => 'sdi'], [ 'user_id' => $this->id, 'valore' => $value ]);
    }
}
