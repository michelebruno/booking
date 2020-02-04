<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->string("token")
                ->primary();
            $table->string("stato");
            $table->unsignedBigInteger("voce_ordine_id");
            $table->unsignedBigInteger("prodotto_id");
            $table->unsignedBigInteger("variante_tariffa_id");
            $table->timestamps();

            $table->foreign("voce_ordine_id")
                ->on("ordini_voci")
                ->references("id")
                ->onDelete("cascade");

            $table->foreign("prodotto_id")
                ->on("prodotti")
                ->references("id");

            $table->foreign("variante_tariffa_id")
                ->on("varianti_tariffa")
                ->references("id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
