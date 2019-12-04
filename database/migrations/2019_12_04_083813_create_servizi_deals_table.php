<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiziDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prodotti_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('padre');
            $table->unsignedBigInteger('figlio');

            $table->foreign('padre')->on('prodotti')->references('id')->onDelete('cascade');
            $table->foreign('figlio')->on('prodotti')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prodotti_pivot');
    }
}
