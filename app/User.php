<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Jenssegers\Mongodb\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property $_id  L'id unico assegnato da Mongo DB.
 * @property $email
 * @property $password
 * @property $ruolo
 * @property $indirizzo
 * @property $nome
 * @property $username
 * @method static whereEmail
 * @method static wherePassword
 * @method static whereRuolo
 * @method static whereIndirizzo
 * @method static whereNome
 * @method static whereUsername
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens;

    protected $connection = "mongodb";

    protected $collection = 'users';

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


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'nome',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        '_id',
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

    /**
     *
     * SCOPES
     *
     */

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('ruolo', self::RUOLO_ADMIN);
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin($query)
    {
        return $query->whereIn('ruolo', ['admin', 'account_manager']);
    }


    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFornitori($query)
    {
        return $query->where('ruolo', self::RUOLO_FORNITORE);
    }

    public function getLinksAttribute()
    {
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

        return [
            "self" => $url_prefix . "/" . $this->getRouteKey()
        ];
    }

    public function getAbilitatoAttribute()
    {
        return $this->attributes['abilitato'] = !$this->trashed();
    }

    /**
     * Questa è la funzione che si occupa del binding nella route. Si potrebbe pensare di
     * sostituirlo per comprendere i
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return parent::resolveRouteBinding($value); // TODO: Change the autogenerated stub
    }

    /**
     * CUSTOM FUNCTIONS
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

    /**
     * Verifica se è un fornitore.
     *
     * @return bool
     */
    public function isFornitore()
    {
        return $this->ruolo === self::RUOLO_FORNITORE;
    }
}
