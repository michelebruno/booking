<?php

namespace App;

/**
 * App\TariffaDeal
 *
 * @property int $id
 * @property int $prodotto_id
 * @property int $variante_tariffa_id
 * @property float $importo
 * @property mixed $imponibile
 * @property-read mixed $iva
 * @property-read mixed $nome
 * @property mixed $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereProdottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereVarianteTariffaId($value)
 * @mixin \Eloquent
 * @property-read \App\Prodotto $prodotto
 * @property-read \App\VarianteTariffa $variante
 */
class TariffaDeal extends Tariffa
{
    //
}
