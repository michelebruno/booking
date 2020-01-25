<?php

namespace App\Listeners\Paypal;

use App\Events\NuovoOrdine;
use App\Mail\DebugMail;
use App\Mail\JsonMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use PayPal\Api\InputFields;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpResponse;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class CreaOrdine
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  NuovoOrdine  $event
     * @return void
     */
    public function handle(\App\Events\NuovoOrdine $event)
    {
        $ordine = $event->ordine;
        
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
            $response = app(PayPalHttpClient::class)->execute($request);

            Log::notice( json_encode( $response ) );            
            
            $ordine->paypal_order_id = $response->result->id;

            $ordine->save();

            $links = new \stdClass;

            foreach ($response->result->links as $link ) {
                $links->{$link->rel} = $link;
            }

            $ordine->meta()->updateOrCreate([ 'chiave' => 'paypal_approve_url' ], [ 'valore' => $links->approve->href  ]);

        } catch (\PayPalHttp\HttpException $th) {
            Log::error( $th->getMessage() );
            Log::error($request->body );
        }



/* 
        // EX


        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $itemList = new ItemList();

        foreach ($ordine->voci as $v ) {
            $item = new Item();
            $item->setName($v->descrizione)
                ->setCurrency('EUR')
                ->setQuantity($v->quantita)
                ->setPrice($v->costo_unitario);

            $itemList->addItem($item);

        }

        $details = new Details();

        $amount = new Amount();
        $amount->setCurrency("EUR")
            ->setTotal($ordine->importo)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setInvoiceNumber($ordine->id);

        $baseUrl = 'http://localhost';

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/OrderGet.php?success=true")
            ->setCancelUrl("$baseUrl/OrderGet.php?success=false");

        // Create the WebProfile
        $inputfields = new \PayPal\Api\InputFields();
        $inputfields->setNoShipping(1);

        $webProfile = new \PayPal\Api\WebProfile();
        $webProfile
        ->setName('Turismo' . uniqid())
        ->setInputFields($inputfields)
        ->setTemporary(true);

        $webProfileId = $webProfile->create( app(\Paypal\Rest\ApiContext::class) )->getId();

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setExperienceProfileId($webProfileId)
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions( array($transaction) );

        try {

            $order = $payment->create( app(\Paypal\Rest\ApiContext::class) );

            $ordine->paypal_order_id = $order->id;

            $ordine->save();

            $ordine->meta()->updateOrCreate([ 'chiave' => 'paypal_approval_url' ], [ 'valore' => $order->getLink('approval_url') ]);


            Mail::to('bm.michelebruno@gmail.com')->send( new JsonMail($order) );

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return dd( $ex->getData() );
        } */
        
    }
}
