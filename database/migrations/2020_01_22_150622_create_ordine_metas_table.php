<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdineMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordini_meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ordine_id');
            $table->string('chiave', 30);
            $table->text('valore');

            $table->foreign('ordine_id')->references('id')->on('ordini')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordini_meta');
    }
}
