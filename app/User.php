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
        'username', 'email', 'password', 'cf', 'piva', 'ruolo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
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
        return $this->hasMany('App\Models\UserMeta', 'user_id' );
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

    public function toArray()
    {
        $array = parent::toArray();

        $array['meta'] = $this->meta;

        return static::toCamelCase($array);
    }

    public function getMetaAttribute()
    { 
        $meta = [];

        foreach ($this->meta()->get() as $m ) {
            $meta[$m->chiave] = $m->valore;
        }

        return $meta;
    }

    public function getAbilitatoAttribute()
    {
        return $this->attributes['abilitato'] = ! $this->trashed();
    }
    
    public function getNomeAttribute()
    {
        return array_key_exists('nome', $this->meta) ? $this->meta['nome'] : null;
    }

    public function getCognomeAttribute()
    {
        return array_key_exists('cognome', $this->meta) ? $this->meta['cognome'] : null;
    }

    public function getDenominazioneAttribute()
    {
        return ( $this->nome && $this->cognome ) ? $this->nome." ".$this->cognome : null;
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
