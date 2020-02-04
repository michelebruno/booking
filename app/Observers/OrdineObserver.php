<?php

namespace App\Observers;

use App\Ordine;
use App\Setting;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class OrdineObserver
{
    /**
     * Handle the ordine "created" event.
     *
     * @param  \App\Ordine  $ordine
     * @return void
     */
    public function created(Ordine $ordine)
    {        
        Setting::progressivo('ordini' , date('Y') )->increment('valore');


    }

    /**
     * Handle the ordine "updated" event.
     *
     * @param  \App\Ordine  $ordine
     * @return void
     */
    public function updated(Ordine $ordine)
    {
        if ( $ordine->importo && !$ordine->paypal_order_id ) { // Crea l'ordine PayPal se non è ancora stato creato.
            $this->creaOrdinePayPal($ordine);
        }
    }

    /**
     * Handle the ordine "deleted" event.
     *
     * @param  \App\Ordine  $ordine
     * @return void
     */
    public function deleted(Ordine $ordine)
    {
        //
    }

    /**
     * Handle the ordine "restored" event.
     *
     * @param  \App\Ordine  $ordine
     * @return void
     */
    public function restored(Ordine $ordine)
    {
        //
    }

    /**
     * Handle the ordine "force deleted" event.
     *
     * @param  \App\Ordine  $ordine
     * @return void
     */
    public function forceDeleted(Ordine $ordine)
    {
        //
    }

    /**
     * Crea un ordine PayPal con una richiesta all'API di PayPal.
     *
     * @param  \App\Ordine  $ordine
     * @return void
     */
    protected function creaOrdinePayPal( Ordine $ordine )
    {
        $payPalHttpClient = app(PayPalHttpClient::class);

        $request = new OrdersCreateRequest;

        $request->prefer('return=representation');

        $items = [];

        foreach ($ordine->voci as $v ) {
            $item = [
                "name" => $v->descrizione,
                "unit_amount" => [
                    "value" => $v->costo_unitario,
                    "currency_code" => "EUR"
                ],
                "quantity" => $v->quantita,
                "sku" => $v->codice,
                "category" => "DIGITAL_GOODS"
            ];

            $items[] = $item;

        }

        $request->body = [
            "intent" => "CAPTURE",
            "payee" => [
                "email_address" => "michelebruno@paypal.com"
            ],
            "invoice_id" => $ordine->id,
            "purchase_units" => [
                [
                    "invoice_id" => $ordine->id,
                    "items" => $items,
                    "amount" => [
                        "value" => $ordine->importo,
                        "currency_code" => "EUR",
                        "breakdown" => [
                            "item_total" => [
                                "value" => $ordine->importo,
                                "currency_code" => "EUR"
                            ]
                        ]
                    ]
                ]
            ],
            "application_context" => [
                "shipping_preference" => "NO_SHIPPING"
            ]

        ];

        try {
            $response = $payPalHttpClient->execute($request);
            
            $ordine->paypal_order_id = $response->result->id;

            $ordine->save();

            $links = new \stdClass;

            foreach ($response->result->links as $link ) {
                $links->{$link->rel} = $link;
            }

            $ordine->meta()->updateOrCreate([ 'chiave' => 'paypal_approve_url' ], [ 'valore' => $links->approve->href  ]);

        } catch (\PayPalHttp\HttpException $th) {
            Log::error( $th->getMessage() , [ 'request' => $request->body ]);
        }
    }
}
