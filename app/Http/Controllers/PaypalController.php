<?php

namespace App\Http\Controllers;

use App\Events\NuovoPagamento;
use App\Mail\DebugMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ApiContext $apiContext)
    { 

        $v = new VerifyWebhookSignature();

        $v->setAuthAlgo( $request->header('PAYPAL-AUTH-ALGO') );
        $v->setTransmissionId( $request->header('PAYPAL-TRANSMISSION-ID') );
        $v->setCertUrl( $request->header('PAYPAL-CERT-URL') );
        $v->setWebhookId( config('services.paypal.webhooks.all') );
        $v->setTransmissionSig( $request->header('PAYPAL-TRANSMISSION-SIG') );
        $v->setTransmissionTime( $request->header('PAYPAL-TRANSMISSION-TIME') );

        $v->setRequestBody( $request->all() );

        try {
            $response = $v->post( $apiContext );

            if ( true || \App::environment('local')) {
                Mail::to('bm.michelebruno@gmail.com')->send(new DebugMail);
            }

            if (
                $request->input('')
            )

            event( NuovoPagamento::class );
            return response();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
