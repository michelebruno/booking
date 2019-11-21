<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Prodotto extends Model
{
    use SoftDeletes;

    protected $table = "prodotti";

    protected $hidden = [
        'esercente_id', 'deleted_at', 'created_at', 'updated_at'
    ];

    public function tariffe()
    {
        return $this->hasMany('App\Models\Tariffa', 'prodotto_id');
    }
}
