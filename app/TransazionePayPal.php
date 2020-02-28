<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * App\TransazionePayPal
 *
 * @property int $id
 * @property string $gateway
 * @property string $transazione_id
 * @property string|null $stato
 * @property float $importo
 * @property string $ordine_id
 * @property string|null $verified_by_event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $verificata
 * @property-read \App\Ordine $ordine
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione transazioneId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereOrdineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereTransazioneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TransazionePayPal whereVerifiedByEventId($value)
 * @mixin \Eloquent
 */
class TransazionePayPal extends \App\Transazione
{

    const COMPLETO = "COMPLETED";
    
    protected $attributes = [
        'gateway' => 'PayPal'
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('gateway_paypal', function (Builder $builder)
        {
            return $builder->where('gateway', 'PayPal');
        });
    }
    
}
