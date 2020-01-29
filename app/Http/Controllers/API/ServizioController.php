<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Servizio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServizioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $this->authorize( 'viewAny' , Servizio::class );

        $query = false;

        if ( $s = $request->query('s', false )) {

            $s = urldecode($s);
            $servizi = Servizio::where('titolo', 'LIKE', '%' . $s . '%' )->get();
            $response = $servizi->load('deals');

        }
        if ( $s = $request->query('s', false ) ) {

            $s = urldecode($s);

            $query = Servizio::where('titolo', 'LIKE', '%' . $s . '%' );

        } 
        
        if ( $notAttachedToDeals = $request->query('notAttachedToDeals', false ) ) { // Separati con la virgola

            if ( ! $query ) $query = Servizio::whereDoesntHave('deals' , function (Builder $query ) use ( $notAttachedToDeals )
            {
                return $query->whereIn('padre' , explode(',' ,  $notAttachedToDeals ) );
            });

            else $query->whereDoesntHave('deals' , function (Builder $query ) use ( $notAttachedToDeals )
            {
                return $query->whereIn('padre' , explode(',' ,  $notAttachedToDeals ) );
            });

        }   

        if ( $query ) { 
            $response = $query->get(); 
        } else $response = Servizio::all()->load('deals');

        return response( $response );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Servizio  $servizio
     * @return \Illuminate\Http\Response
     */
    public function show(Servizio $servizio)
    {
        $this->authorize('view', $servizio);

        return response( $servizio );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Servizio  $servizio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Servizio $servizio)
    {
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Servizio  $servizio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servizio $servizio)
    {
        // TODO
    }
}
