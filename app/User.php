<?php

namespace App;

use App\Traits\HaAttributiMeta;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property int $id
 * @property string $email
 * @property string|null $username
 * @property string|null $nome
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $api_token
 * @property string|null $cf
 * @property string|null $piva
 * @property string $ruolo
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read mixed $abilitato
 * @property-read mixed $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User email($email)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User esercente()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRuolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User esercenti()
 */
class User extends Authenticatable implements MustVerifyEmail
{

    use HaAttributiMeta;

    const RUOLO_ADMIN = "admin";
    const RUOLO_ACCOUNT = "account_manager";
    const RUOLO_CLIENTE = "cliente";
    const RUOLO_ESERCENTE = "esercente";

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
        'abilitato' , '_links'
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

    public function scopeEsercenti($query)
    {
        return $query->where('ruolo', self::RUOLO_ESERCENTE );
    }

    public function meta()
    {
        return $this->hasMany('App\UserMeta', 'user_id' );
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


    public function getLinksAttribute()
    {
        return [];
    }

    public function getAbilitatoAttribute()
    {
        return $this->attributes['abilitato'] = ! $this->trashed();
    }
    /**
     * * CUSTOM FUNCTIONS
     */

    public function isEsercente()
    {
        return $this->ruolo === self::RUOLO_ESERCENTE;
    }


}
