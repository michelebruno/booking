<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Fornitura;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FornituraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $this->authorize( 'viewAny' , Fornitura::class );

        $query = false;

        if ( $s = $request->query('s', false ) ) {

            $s = urldecode($s);
            $servizi = Fornitura::where('titolo', 'LIKE', '%' . $s . '%' )->get();
            $response = $servizi->loadMissing('deals');

        }
        
        if ( $notAttachedToDeals = $request->query('notAttachedToDeals', false ) ) { // Separati con la virgola

            if ( ! $query ) $query = Fornitura::whereDoesntHave('deals' , function (Builder $query ) use ( $notAttachedToDeals )
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
        } else $response = Fornitura::all()->loadMissing('deals');

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
     * @param  \App\Fornitura  $fornitura
     * @return \Illuminate\Http\Response
     */
    public function show(Fornitura $fornitura)
    {
        $this->authorize('view', $fornitura);

        return response( $fornitura );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fornitura  $fornitura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fornitura $fornitura)
    {
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fornitura  $fornitura
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fornitura $fornitura)
    {
        // TODO
    }
}
