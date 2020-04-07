<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdottosTable extends Migration
{

    protected $connection = "mongodb";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('prodotti', function ($collection) {
            $collection->unique('codice');
        });
        /* Schema::create('prodotti', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titolo');
            $table->string('codice')->unique();
            $table->string('tipo');
            $table->longText('descrizione')
                ->nullable()
                ->default(null);
            $table->bigInteger('fornitore_id')
                ->unsigned()
                ->nullable()
                ->default(null);
            $table->string('stato', 20);
            $table->integer('disponibili', false, true)
                ->nullable()
                ->default(0);
            $table->tinyInteger('iva', false, true);
            $table->integer('wp', false, true)
                ->nullable()
                ->default(null);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fornitore_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('prodotti_meta', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('prodotto_id')
                ->unsigned();
            $table->string('chiave');
            $table->string('valore');

            $table->foreign('prodotto_id')
                ->references('id')
                ->on('prodotti')
                ->onDelete('cascade');
        }); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodotti');
    }
}
