<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CambioTerminologiaEsercenti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('prodotti', function (Blueprint $table) {
            $table->dropForeign(['esercente_id']);
            
            $table->renameColumn( 'esercente_id', 'fornitore_id' );

            $table->foreign('fornitore_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        DB::table('prodotti')->where('tipo', 'servizio')->update([ 'tipo' => 'fornitura' ]);
        
        DB::table('users')->where('ruolo', 'esercente' )->update(['ruolo' => 'fornitore'] );
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prodotti', function (Blueprint $table) {            
            
            $table->dropForeign(['fornitore_id']);

            $table->renameColumn('fornitore_id', 'esercente_id');

            $table->foreign('esercente_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

        });

        DB::table('users')->where('ruolo', 'fornitore' )->update(['ruolo' => 'esercenti'] );

    }
}
