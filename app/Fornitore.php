<?php

namespace App;

use App\Traits\HaIndirizzo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * App\Fornitore
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Fornitura[] $forniture
 * @property-read int|null $forniture_count
 * @property-read mixed $abilitato
 * @property mixed $indirizzo
 * @property-read mixed $links
 * @property mixed $note
 * @property mixed $pec
 * @property mixed $ragione_sociale
 * @property mixed $s_d_i
 * @property mixed $sede_legale
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User admin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User fornitori()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User superAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereCf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore wherePiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereRuolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fornitore whereUsername($value)
 * @mixin \Eloquent
 */
class Fornitore extends User
{
    use HaIndirizzo;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password', 
        'cf', 
        'piva', 
        'nome'
    ];

    protected $attributes = [
        'ruolo' => self::RUOLO_FORNITORE,
    ];

    protected $appends = [
        'pec', 
        'sdi', 
        'indirizzo', 
        'sede_legale', 
        'ragione_sociale', 
        '_links', 
        'abilitato', 
        'note'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('RUOLO_FORNITORE', function (Builder $builder) {
            return $builder->where('ruolo', self::RUOLO_FORNITORE );
        });
    }

    public function forniture()
    {
        return $this->hasMany( Fornitura::class );
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['meta'] = Arr::except($this->meta, ['sdi', 'pec', 'ragione_sociale', 'sede_legale']);

        return $array;
    }

    public function getLinksAttribute()
    {
        $base_uri = '/fornitori/'. $this->id;

        return [
            'self' => $base_uri ,
            'edit' => $base_uri  . '/modifica',
            'delete' => $base_uri ,
            'restore' => $base_uri  . '/restore',
            'forniture' => $base_uri  . '/forniture'
        ];
    }

    /*
     * ANAGRAFICA 
     */

    public function setCfAttribute($value)
    {
        return $this->attributes['cf'] = trim(strtoupper($value));
    }

    public function getRagioneSocialeAttribute()
    {
        return $this->getMeta('ragione_sociale');
    }

    public function setRagioneSocialeAttribute($value)
    {
        return $this->setMeta('ragione_sociale', $value);
    }

    public function getPecAttribute()
    {
        return $this->getMeta('pec');
    }

    public function setPecAttribute($value)
    {
        return $this->setMeta('pec', $value);
    }

    public function getNoteAttribute()
    {
        return $this->getMeta('note');
    }

    public function setNoteAttribute($value)
    {
        return $this->setMeta('note', $value);
    }

    public function getSDIAttribute()
    {
        return $this->getMeta('SDI');
    }

    public function setSDIAttribute($value)
    {
        return $this->setMeta('SDI', $value);
    }
}
