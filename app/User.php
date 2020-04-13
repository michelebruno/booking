<?php

namespace App;

use App\Traits\HaAttributiMeta;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Jenssegers\Mongodb\Auth\User as Authenticatable;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User admin()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User fornitori()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User superAdmin()
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
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens; //, HaAttributiMeta;

    const RUOLI = [
        "admin",
        "account_manager",
        "fornitore",
        "cliente"
    ];

    const RUOLO_ADMIN = "admin";

    const RUOLO_ACCOUNT = "account_manager";

    const RUOLO_CLIENTE = "cliente";

    const RUOLO_FORNITORE = "fornitore";

    protected $connection = "mongodb";

    protected $collection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cf',
        'email',
        'username',
        'password',
        'piva',
        'ruolo',
        'nome'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $appends = [
        'abilitato',
        '_links'
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

    public function scopeSuperAdmin($query)
    {
        return $query->where('ruolo', self::RUOLO_ADMIN);
    }

    public function scopeAdmin($query)
    {
        return $query->whereIn('ruolo', ['admin', 'account_manager']);
    }


    public function scopeFornitori($query)
    {
        return $query->where('ruolo', self::RUOLO_FORNITORE);
    }

    /* 
    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }
    */

    public function getLinksAttribute()
    {
        $url_prefix = "";

        switch ($this->ruolo) {
            case self::RUOLO_ACCOUNT:
            case self::RUOLO_ADMIN:
                /**
                 * TODO creare una route "utenti"
                 */
                $url_prefix = "/users";
                break;

            case self::RUOLO_FORNITORE:
                $url_prefix = "/fornitore";
                break;

            default:
                $url_prefix = "/users";
                break;
        }

        $links = [
            "self" => $url_prefix . "/" . $this->getRouteKey()
        ];

        return $links;
    }

    public function getAbilitatoAttribute()
    {
        return $this->attributes['abilitato'] = !$this->trashed();
    }
    /**
     * * CUSTOM FUNCTIONS
     */

    public function isSuperAdmin()
    {
        return $this->ruolo === self::RUOLO_ADMIN;
    }

    /**
     * Verifica se l'utente è un gestore del sistema.
     * 
     * Ritorna true se appartiene ai ruoli: admin, account_manager.
     *
     * @return bool 
     */
    public function isAdmin()
    {
        return in_array($this->ruolo, [self::RUOLO_ACCOUNT, self::RUOLO_ADMIN]);
    }

    /**
     * Verifica se è un account manager.
     *
     * @return bool
     */
    public function isAccountManager()
    {
        return $this->ruolo === self::RUOLO_ACCOUNT;
    }

    public function isFornitore()
    {
        return $this->ruolo === self::RUOLO_FORNITORE;
    }
}
