<?php

namespace App;

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
    
    public function scopeAutoloaded($query)
    {
        return $query->where('autoload', true );
    }

    public function scopeProgressivo($query)
    {
        $args = func_get_args();

        unset($args[0]);
        
        $s = join("_", $args);
        
        return $query->firstOrCreate( [ 'chiave' => '_progressivo_' . $s ] , [ 'valore' => 1 , 'autoload' => false] );
    }
}
