<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantePrezzosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('varianti_tariffa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('nome');
            $table->unsignedBigInteger('fallback_id')
                ->nullable()
                ->default(null);
            $table->timestamps();

            $table->foreign('fallback_id')
                ->on('varianti_tariffa')
                ->references('id');
        });

        Schema::table('tariffe', function (Blueprint $table) {
            $table->foreign('variante_tariffa_id')
                ->on('varianti_tariffa')
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
        // TODO è giusto?
        Schema::table('tariffe', function (Blueprint $table) {
            $table->dropForeign('tariffe_variante_prezzo_id_foreign');
        });

        Schema::dropIfExists('varianti_prezzo');
    }
}
