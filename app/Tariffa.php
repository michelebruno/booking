<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tariffa
 *
 * @property \MongoDB\BSON\ObjectId _id
 * @property string slug
 * @property string nome
 * @method whereSlug
 * @method whereNome
 */
class Tariffa extends Model
{
    protected $table = "varianti_tariffa";

    protected $connection = 'mysql';

    protected $guarded = [
        "id"
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function prodotti()
    {
        return $this->hasManyThrough('App\Prodotto', 'App\Importo');
    }

}
