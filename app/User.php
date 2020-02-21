<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens;

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
        'password', 'remember_token' 
    ];

    protected $appends = [
        'abilitato' , '_links', 'meta'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /* 
     *
     * SCOPES
     * 
     */

    public function scopeEsercente($query)
    {
        return $query->where('ruolo', 'esercente');
    }

    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email)->firstOrFail();
    }
    

    public function meta()
    {
        return $this->hasMany('App\UserMeta', 'user_id', 'id' );
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

    public function getMetaAttribute()
    { 
        $meta = $this->meta()->get();
        
        return $meta->mapWithKeys(function ($item) {
            return [ $item['chiave'] => $item["valore"] ];
        });
    }

    public function getLinksAttribute()
    {
        return [];
    }

    public function getAbilitatoAttribute()
    {
        return $this->attributes['abilitato'] = ! $this->trashed();
    }
    
    protected function _getPrefixedIndirizzo($prefix)
    {
        $indirizzo = [];
        
        $meta = $this->meta;
        
        if ( Arr::exists( $this->meta , $prefix . 'via' ) ) {
            $indirizzo['via'] = $this->meta[$prefix . 'via'];
        }
        
        if ( Arr::exists( $meta , $prefix . 'civico' ) ) {
            $indirizzo['civico'] = $meta[$prefix . 'civico'];
        }
        
        if ( Arr::exists( $meta , $prefix . 'citta' ) ) {
            $indirizzo['citta'] = $meta[$prefix . 'citta'];
        }
        
        if ( Arr::exists( $meta , $prefix . 'provincia' ) ) {
            $indirizzo['provincia'] = $meta[$prefix . 'provincia'];
        }
        
        if ( Arr::exists( $meta , $prefix . 'cap' ) ) {
            $indirizzo['cap'] = $meta[$prefix . 'cap'];
        }

        return $indirizzo;
    }

}
