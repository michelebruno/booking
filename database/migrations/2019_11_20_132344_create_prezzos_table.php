<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrezzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffe', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prodotto_id');
            $table->unsignedBigInteger('variante_tariffa_id');
            $table->float('imponibile'); // 00'0000,00

            $table->foreign('prodotto_id')
                ->on('prodotti')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffe');
    }
}
