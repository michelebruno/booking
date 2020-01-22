<?php

namespace App\Listeners;

use App\Events\NuovoOrdine;
use App\Mail\DebugMail;
use App\Mail\JsonMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PayPal\Api\InputFields;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class CreaOrdinePaypal
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
    public function handle(\App\Ordine $ordine)
    {
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
        }
        
    }
}
