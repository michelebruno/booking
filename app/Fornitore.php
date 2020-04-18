<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\Fornitore
 *
 * @property $piva
 * @property $pec
 * @property $SDI
 * @property $cf
 * @property object $indirizzo
 * @property object $sede_legale
 */
class Fornitore extends User
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'nome',
        'cf',
        'piva',
        'nome',
        'pec',
        'ragione_sociale',
        'sede_legale',
        'indirizzo'
    ];

    protected $attributes = [
        'ruolo' => self::RUOLO_FORNITORE,
    ];

    protected $appends = [
        '_links',
        'abilitato',
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('RUOLO_FORNITORE', function (Builder $builder) {
            return $builder->where('ruolo', self::RUOLO_FORNITORE);
        });
    }

    public function forniture()
    {
        return $this->hasMany(Fornitura::class);
    }

    public function getLinksAttribute()
    {
        $base_uri = '/fornitori/' . $this->getRouteKey();

        return [
            'self' => $base_uri,
            'edit' => $base_uri . '/modifica',
            'delete' => $base_uri,
            'restore' => $base_uri . '/restore',
            'forniture' => $base_uri . '/forniture'
        ];
    }

    /*
     * MUTATORS
     */
    public function setCfAttribute($value)
    {
        return $this->attributes['cf'] = trim(strtoupper($value));
    }
}
