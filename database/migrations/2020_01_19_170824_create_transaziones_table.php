<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransazionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gateway');
            $table->string('transazione_id');
            $table->float('importo', 12, 2);
            $table->string('ordine_id');
            $table->string('verified_by_event_id')
                ->nullable()
                ->default(null);
            $table->timestamps();

            $table->foreign('ordine_id')->on('ordini')->references('id'); // TODO onDelete...
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transazioni');
    }
}
