<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('email')
                ->unique();
                
            $table->string('username')
                ->unique()
                ->nullable();
                
            $table->string('nome')
                ->nullable()
                ->default(null);
                
            $table->timestamp('email_verified_at')
                ->nullable();
                
            $table->string('password')
                ->nullable()
                ->default(null); 
                
            $table->string('cf', 16)
                ->unique()
                ->nullable()
                ->default(null);
                
            $table->string('piva', 11)
                ->unique()
                ->nullable()
                ->default(null);
                
            $table->string('ruolo', 20);
             
            $table->rememberToken();
            
            $table->timestamps();
            
            $table->softDeletes();
            
        }); 

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
