<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->useCurrent = true;
            $table->boolean('payed')->default(false);
            $table->double('amount')->nullable();
            $table->integer('user_owns')->unsigned();
            $table->integer('user_lent')->unsigned();
            $table->timestamps();

            $table->foreign('user_owns')->references('id')->on('users');
            $table->foreign('user_lent')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settles');
    }
}
