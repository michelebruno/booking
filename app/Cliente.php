<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Cliente
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ordine[] $ordini
 * @property-read int|null $ordini_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User admin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User fornitori()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User superAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereCf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente wherePiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereRuolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente whereUsername($value)
 * @mixin \Eloquent
 */
class Cliente extends User
{
    protected $attributes = [
        "ruolo" => self::RUOLO_CLIENTE
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('ruolo_cliente', function (Builder $builder)
        {
            return $builder->whereRuolo( self::RUOLO_CLIENTE );
        });
    }

    public function ordini()
    {
        return $this->hasMany('App\Ordine', 'cliente_id');
    }

    public function getLinksAttribute()
    {
        return [
            'self' => '/clienti/' . $this->id,
            'forceDelete' => '/clienti/' . $this->id . '/forceDelete'
        ];
    }
}
