<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlipsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slips_items', function (Blueprint $table) {
            $table->integer('slip_id')->unsigned()->nullable();
            $table->foreign('slip_id')->references('id')
              ->on('slips')->onDelete('cascade');

            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')
              ->on('items')->onDelete('cascade');

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slips_items');
    }
}
