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
    public function store( Request $request )
    { 
        // TODO 
        $v = new VerifyWebhookSignature();

        try {

            $v->setAuthAlgo( $request->header('PAYPAL-AUTH-ALGO') );
            $v->setTransmissionId( $request->header('PAYPAL-TRANSMISSION-ID') );
            $v->setCertUrl( $request->header('PAYPAL-CERT-URL') );
            $v->setWebhookId( config('services.paypal.webhooks.all') );
            $v->setTransmissionSig( $request->header('PAYPAL-TRANSMISSION-SIG') );
            $v->setTransmissionTime( $request->header('PAYPAL-TRANSMISSION-TIME') );
    
            $v->setRequestBody( file_get_contents('php://input') );
            
            if ( $v->post( app(ApiContext::class) )->getVerificationStatus() === "SUCCESS" ) {

                if ( $event_type = $request->input('event_type', false) ) {

                    switch ($event_type) {
                        case 'PAYMENTS.PAYMENT.CREATED':
                        case 'PAYMENT.CAPTURE.PENDING':
                            event( new PaymentCapture( $request->input('resource') , true, $request->all()) );
                            return response( null , 201);
                            break;
    
                        case 'CHECKOUT.ORDER.APPROVED':
                            # code...
                            break;
                        
                        default:
                            Log::warning("Il Webhook di PayPal ha registrato un evento attualmente non previsto: $event_type" );
                            return response( null, 200);
                            break;
                    }

                }

            } else {
                Log::warning('FIRMA DI UNA NOTIFICA PAYPAL NON VERIFICATA',  [ "notifica" => $request->all() , "request" => $request ]);
                return response("FIRMA NON VERIFICATA", 400);
            }

        } catch ( \InvalidArgumentException $th ) {
            Log::error('Probabilmente è stato tentato una finta chiamata al webhook di PayPal', [ 'exception' => $th , 'request' => $request ]);
            abort(400, $th->getMessage() );
        } catch ( \PayPal\Exception\PayPalConnectionException $th ) {
            Log::error('Probabilmente è stato tentato una finta chiamata al webhook di PayPal', [ 'exception' => $th , 'request' => $request ] );
            return response("", 400);
        }
    }
}
