<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'users_meta';

    public $timestamps = false;

    public $touches = [ 'user' ];

    public $fillable = [
        'chiave',
        'valore'
    ];

    public function user()
    {
        $this->belongsTo('App/User');
    }
    
}
