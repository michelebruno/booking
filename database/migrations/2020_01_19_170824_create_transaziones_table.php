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
        Schema::connection("mysql")->create('transazioni', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gateway');
            $table->string('transazione_id');
            $table->string('stato')
                ->nullable()
                ->default(null);
            $table->float('importo', 12, 2);
            $table->string('ordine_id');
            $table->string('verified_by_event_id')
                ->nullable()
                ->default(null);
            $table->timestamps();

            $table->unique(['transazione_id', 'gateway']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection("mysql")->dropIfExists('transazioni');
    }
}
