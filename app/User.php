<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'cf', 'piva', 'ruolo', 'nome'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    protected $appends = [
        'abilitato'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function meta()
    {
        return $this->hasMany('App\Models\UserMeta', 'user_id', 'id' );
    }

    public static function toCamelCase(array $array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {

            if ( is_array($value) ) $value = static::toCamelCase($value);

            $key = lcfirst(implode('', array_map('ucfirst', explode('_', $key))));

            $newArray[$key] = $value;
        }

        return $newArray;
    }

    public function exceptFromTraits($class, $array = false )
    {
        if ( $array === false ) $array = $this->meta;
        $except = [];

        $traits = class_uses_recursive($class);

        if ( in_array('App\Traits\HaIndirizzo', $traits) ) {
            $except = array_merge($traits, ['indirizzo_via','indirizzo_civico','indirizzo_citta','indirizzo_provincia','indirizzo_cap']);
        }

        return Arr::except($array, $except);
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['meta'] = $this->meta;
        
        return $array;
    }

    public function getMetaAttribute()
    { 
        $meta = [];

        $array = $this->meta()->get();

        foreach ( $array as $m ) {
            $meta[$m->chiave] = $m->valore;
        }

        return $meta;
    }

    public function getAbilitatoAttribute()
    {
        return $this->attributes['abilitato'] = ! $this->trashed();
    }
    
    /* 
     *
     * SCOPES
     * 
     */

    public function scopeEsercente($query)
    {
        return $query->where('ruolo', 'esercente');
    }
}
