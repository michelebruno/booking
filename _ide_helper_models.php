<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
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
 * @property-read mixed $abilitato
 * @property-read mixed $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ordine[] $ordini
 * @property-read int|null $ordini_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User email($email)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User esercente()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cliente query()
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
 */
	class Cliente extends \Eloquent {}
}

namespace App{
/**
 * App\Deal
 *
 * @property int $id
 * @property string $titolo
 * @property string $codice
 * @property string $tipo
 * @property string|null $descrizione
 * @property int|null $esercente_id
 * @property string $stato
 * @property int|null $disponibili
 * @property int $iva
 * @property int|null $wp
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read mixed $links
 * @property-read string $smart
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tariffa[] $tariffe
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Servizio[] $servizi
 * @property-read int|null $servizi_count
 * @property-read int|null $tariffe_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto attivi()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto codice($codice)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereEsercenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Deal whereWp($value)
 */
	class Deal extends \Eloquent {}
}

namespace App{
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
 */
	class Esercente extends \Eloquent {}
}

namespace App{
/**
 * App\Ordine
 *
 * @property string  $stato
 *      - INIT se è in fase di creazione
 *      - APERTO quando deve essere pagato dal cliente
 *      - ELABORAZIONE se il pagamento è in stato di verifica
 *      - PAGATO se è stato pagato ma non sono stati generati i ticket
 *      - ELABORATO se i tickets stati generati e inviati
 *      - CHIUSO se tutti i tickets sono stati usati
 *      - RIMBORSATO se è stato rimborsato // ? Anche solo parzialmente?
 * @property string $id
 * @property float|null $imponibile
 * @property float|null $imposta
 * @property float|null $importo
 * @property float|null $dovuto
 * @property int $cliente_id
 * @property string|null $data
 * @property string|null $paypal_order_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cliente $cliente
 * @property-read mixed $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\OrdineMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Transazione[] $transazioni
 * @property-read int|null $transazioni_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VoceOrdine[] $voci
 * @property-read int|null $voci_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereDovuto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereImponibile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereImposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine wherePaypalOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ordine withAll()
 */
	class Ordine extends \Eloquent {}
}

namespace App{
/**
 * App\OrdineMeta
 *
 * @property int $id
 * @property string $ordine_id
 * @property string $chiave
 * @property string $valore
 * @property-read \App\Ordine $ordine
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereChiave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereOrdineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrdineMeta whereValore($value)
 */
	class OrdineMeta extends \Eloquent {}
}

namespace App{
/**
 * App\Prodotto
 *
 * @property int $id
 * @property string $titolo
 * @property string $codice
 * @property string $tipo
 * @property string|null $descrizione
 * @property int|null $esercente_id
 * @property string $stato
 * @property int|null $disponibili
 * @property int $iva
 * @property int|null $wp
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read array $links
 * @property-read string $smart
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tariffa[] $tariffe
 * @property-read int|null $tariffe_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto attivi()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto codice($codice)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Prodotto onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereEsercenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto whereWp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Prodotto withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Prodotto withoutTrashed()
 */
	class Prodotto extends \Eloquent {}
}

namespace App{
/**
 * App\Servizio
 *
 * @property int $id
 * @property string $titolo
 * @property string $codice
 * @property string $tipo
 * @property string|null $descrizione
 * @property int|null $esercente_id
 * @property string $stato
 * @property int|null $disponibili
 * @property int $iva
 * @property int|null $wp
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Deal[] $deals
 * @property-read int|null $deals_count
 * @property \App\Esercente|null $esercente
 * @property-read mixed $cestinato
 * @property-read mixed $condensato
 * @property-read mixed $links
 * @property-read string $smart
 * @property \Illuminate\Database\Eloquent\Collection|\App\Tariffa[] $tariffe
 * @property-read int|null $tariffe_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto attivi()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto codice($codice)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Prodotto disponibili($more_than = 0)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio fornitore($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereDisponibili($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereEsercenteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereTitolo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Servizio whereWp($value)
 */
	class Servizio extends \Eloquent {}
}

namespace App{
/**
 * App\Tariffa
 *
 * @property int $id
 * @property int $prodotto_id
 * @property int $variante_tariffa_id
 * @property float $importo
 * @property mixed $imponibile
 * @property-read mixed $iva
 * @property-read mixed $nome
 * @property mixed $slug
 * @property-read \App\Prodotto $prodotto
 * @property-read \App\VarianteTariffa $variante
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereProdottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tariffa whereVarianteTariffaId($value)
 */
	class Tariffa extends \Eloquent {}
}

namespace App{
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
 * @property-read \App\Prodotto $prodotto
 * @property-read \App\VarianteTariffa $variante
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereProdottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TariffaDeal whereVarianteTariffaId($value)
 */
	class TariffaDeal extends \Eloquent {}
}

namespace App{
/**
 * App\Ticket
 *
 * @property string $stato può essere:
 *      - APERTO
 *      - SCADUTO
 *      - CHIUSO
 * @property string $token
 * @property int $voce_ordine_id
 * @property int $prodotto_id
 * @property int $variante_tariffa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Ordine $ordine
 * @property-read \App\VarianteTariffa $varianteTariffa
 * @property-read \App\VoceOrdine $voce
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereProdottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereVarianteTariffaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereVoceOrdineId($value)
 */
	class Ticket extends \Eloquent {}
}

namespace App{
/**
 * App\Transazione
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione transazioneId($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereOrdineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereStato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereTransazioneId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Transazione whereVerifiedByEventId($value)
 */
	class Transazione extends \Eloquent {}
}

namespace App{
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
 */
	class TransazionePayPal extends \Eloquent {}
}

namespace App{
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
 * @property-read mixed $abilitato
 * @property-read mixed $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
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
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\UserMeta
 *
 * @property int $id
 * @property int $user_id
 * @property string $chiave
 * @property string $valore
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereChiave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereValore($value)
 */
	class UserMeta extends \Eloquent {}
}

namespace App{
/**
 * App\VarianteTariffa
 *
 * @property int $id
 * @property string $slug
 * @property string $nome
 * @property int|null $fallback_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Prodotto[] $prodotti
 * @property-read int|null $prodotti_count
 * @property-read \App\Tariffa $tariffe
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereFallbackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VarianteTariffa whereUpdatedAt($value)
 */
	class VarianteTariffa extends \Eloquent {}
}

namespace App{
/**
 * App\VoceOrdine
 *
 * @property string  $ordine_id
 * @property int  $prodotto_id
 * @property string  $codice
 * @property int  $quantita
 * @property float{10,2}  $costo_unitario
 * @property \App\Prodotto  $prodotto
 * @property \App\Tariffa  $tariffa
 * @property \App\Tickets[]  $tickets
 * @property int $id
 * @property string|null $descrizione
 * @property int|null $tariffa_id
 * @property int $iva
 * @property float $imponibile
 * @property float $imposta
 * @property float $importo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $riscattati
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereCodice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereCostoUnitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereDescrizione($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereImponibile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereImporto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereImposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereIva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereOrdineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereProdottoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereQuantita($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereTariffaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VoceOrdine whereUpdatedAt($value)
 */
	class VoceOrdine extends \Eloquent {}
}

