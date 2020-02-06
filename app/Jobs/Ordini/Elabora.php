<?php

namespace App\Jobs\Ordini;

use App\Events\OrdinePagato;
use App\Ordine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Elabora implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $ordini = Ordine::whereIn( "stato" , [ Ordine::ELABORAZIONE , Ordine::PAGATO ] )->where("dovuto", 0)->with(['transazioni'])->get();

        $elaboro = $ordini->reject( function ($ordine) {
            return ! $ordine->transazioni->every( function ( $transazione ) {
                return $transazione->verified_by_event_id;
            });
        })->map(function ($ordine) {
            event( new OrdinePagato($ordine) );
            return $ordine;
        });

        return $elaboro->count() . " dei " . $ordini->count() . " iniziali";

    }
}
