<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordini', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('stato');
            $table->float('imponibile', 12, 2)
                ->nullable()
                ->default(null);
            $table->float('imposta', 12, 2)
                ->nullable()
                ->default(null);
            $table->float('importo', 12, 2)
                ->nullable()
                ->default(null);
            $table->unsignedBigInteger('cliente_id');
            $table->date('data')
                ->nullable()
                ->default(null);
            $table->timestamps();

            $table->foreign('cliente_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordini');
    }
}
