<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'cf', 'piva'
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
        return $this->hasMany('App\Models\UserMeta');
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['meta'] = $this->meta;

        $array['abilitato'] = $this->abilitato;

        return $array;
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
}
