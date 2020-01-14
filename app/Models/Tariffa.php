<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariffa extends Model
{
    protected $table = "tariffe";

    protected $appends = [
        'imponibile', 'slug' , 'nome'
    ];    

    public $fillable = [
        'variante_tariffa_id', 'imponibile', 'importo', 'slug'
    ];

    public $timestamps = false;

    public function prodotto()
    {
        return $this->belongsTo('App\Prodotto', 'prodotto_id', 'id');
    }

    public function variante()
    {
        return $this->belongsTo('App\Models\VarianteTariffa', 'variante_tariffa_id');
    }

    public function getSlugAttribute()
    {
        return $this->variante->slug;
    }

    public function setSlugAttribute( $slug )
    {
        return $this->attributes['variante_tariffa_id'] = VarianteTariffa::where('slug', $slug)->firstOrFail()->id;
    }

    public function getNomeAttribute()
    {
        return $this->variante->nome;
    }

    public function getIvaAttribute()
    {
        return $this->prodotto->iva;
    }
    
    public function getImponibileAttribute()
    {
        // TODO farlo funzionare senza la query
        $iva = $this->prodotto()->withTrashed()->first()->iva; 
        return round( $this->importo / ( 1 + $iva / 100 ) , 2 );
    }
    
    public function setImponibileAttribute( $value )
    {
        return $this->attributes['importo'] = round( $value * ( 1 + $this->iva / 100 ) , 2 );
    }
}
