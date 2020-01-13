<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoceOrdinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordini_voci', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ordine_id');
            $table->unsignedBigInteger('prodotto_id');
            $table->string('codice');
            $table->text('descrizione')
                ->default(null)
                ->nullable();
            $table->float('costo_unitario', 8, 2);
            $table->unsignedBigInteger('tariffa_id');
            $table->tinyInteger('quantita');
            $table->tinyInteger('iva', false, true);
            $table->float('imponibile', 10, 2);
            $table->float('imposta', 10, 2);            
            $table->float('importo', 10, 2);
            $table->timestamps();

            $table->foreign('prodotto_id')->on('prodotti')->references('id'); // TODO onDelete()
            $table->foreign('tariffa_id')->on('tariffe')->references('id'); // TODO onDelete()
            $table->foreign('ordine_id')->on('ordini')->references('id'); // TODO onDelete()
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordini_voci');
    }
}
