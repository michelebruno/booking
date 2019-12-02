<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariffa extends Model
{
    protected $table = "tariffe";

    protected $appends = [
        'slug' , 'nome'
    ];

    public $fillable = [
        'variante_tariffa_id', 'imponibile', 'slug'
    ];

    public $timestamps = false;

    public function prodotto()
    {
        return $this->belongsTo('App\Prodotto');
    }

    public function variante()
    {
        return $this->belongsTo('App\Models\VarianteTariffa', 'variante_tariffa_id');
    }

    public function getSlugAttribute()
    {
        return $this->variante->slug;
    }

    public function setSlugAttribute($slug)
    {
        return $this->attributes['variante_tariffa_id'] = VarianteTariffa::where('slug', $slug)->firstOrFail()->id;
    }

    public function getNomeAttribute()
    {
        return $this->variante->nome;
    }
}
