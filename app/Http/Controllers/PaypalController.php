<?php

namespace App\Http\Controllers;

use App\Events\PayPal\PaymentCapture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        // TODO 
/*      $v = new VerifyWebhookSignature();

        $v->setAuthAlgo( $request->header('PAYPAL-AUTH-ALGO') );
        $v->setTransmissionId( $request->header('PAYPAL-TRANSMISSION-ID') );
        $v->setCertUrl( $request->header('PAYPAL-CERT-URL') );
        $v->setWebhookId( config('services.paypal.webhooks.all') );
        $v->setTransmissionSig( $request->header('PAYPAL-TRANSMISSION-SIG') );
        $v->setTransmissionTime( $request->header('PAYPAL-TRANSMISSION-TIME') );

        $v->setRequestBody( $request->all() );

            $response = $v->post( $apiContext ); */

            if ( $event_type = $request->input('event_type', false) ) {
                switch ($event_type) {
                    case 'PAYMENTS.PAYMENT.CREATED':
                    case 'PAYMENT.CAPTURE.PENDING':
                        event( new PaymentCapture( $request->input('resource') , true, $request->all()) );
                        return response( null , 201);
                        break;
                    
                    default:
                        Log::warning("Il Webhook di PayPal ha registrato un evento attualmente non previsto: $event_type" );
                        return response(null, 200);
                        break;
                }
            }

    }
}
