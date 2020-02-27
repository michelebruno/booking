<?php

namespace App;

use App\Traits\HaIndirizzo;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * App\Esercente
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
 * @property mixed $indirizzo
 * @property-read mixed $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $meta
 * @property mixed $note
 * @property mixed $pec
 * @property mixed $ragione_sociale
 * @property mixed $s_d_i
 * @property mixed $sede_legale
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Servizio[] $servizi
 * @property-read int|null $servizi_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User email($email)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User esercente()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereCf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente wherePiva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereRuolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Esercente whereUsername($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User esercenti()
 */
class Esercente extends User
{
    use HaIndirizzo;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'cf', 'piva', 'nome'
    ];

    protected $attributes = [
        'ruolo' => 'esercente'
    ];

    protected $appends = [
        'pec', 'sdi', 'indirizzo', 'sede_legale', 'ragione_sociale', '_links', 'abilitato', 'note'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('ruolo_esercente', function (Builder $builder)
        {
            return $builder->where('ruolo', 'esercente');
        });
    }

    public function servizi()
    {
        return $this->hasMany('App\Servizio');
    }

    public function toArray()
    {
        $array = parent::toArray();

        $meta = $this->exceptFromTraits( self::class );

        $array['meta'] = Arr::except($meta, [ 'sdi', 'pec', 'ragione_sociale', 'sede_legale' ]); 

        return $array;
    }

    public function getLinksAttribute()
    {
        return [
            'self' => '/esercenti/' . $this->id,
            'edit' => '/esercenti/' . $this->id. '/modifica',
            'delete' => '/esercenti/' . $this->id,
            'restore' => '/esercenti/' . $this->id . '/restore',
            'servizi' => '/esercenti/' . $this->id . '/servizi'
        ];
    }

    /*
     * ANAGRAFICA 
     */

    public function setCfAttribute( $value )
    {
        return $this->attributes['cf'] = trim( strtoupper( $value ) );
    }

    public function getRagioneSocialeAttribute()
    {
        return $this->getMeta('ragione_sociale');
    }

    public function setRagioneSocialeAttribute( $value )
    {
        return $this->setMeta('ragione_sociale', $value);
    }

    public function getPecAttribute()
    {
        return $this->getMeta('pec');
    }

    public function setPecAttribute( $value )
    {        
        return $this->setMeta('pec', $value);
    }

    public function getNoteAttribute()
    {
        return $this->getMeta('note');
    }

    public function setNoteAttribute( $value )
    {
        return $this->setMeta('note', $value);
    }

    public function getSDIAttribute()
    {        
        return $this->getMeta('SDI');

    }

    public function setSDIAttribute( $value )
    {        
        return $this->setMeta('SDI', $value);
    }

}
