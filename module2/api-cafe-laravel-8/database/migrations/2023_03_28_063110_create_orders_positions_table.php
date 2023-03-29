<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_positions', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('position_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->integer('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_positions');
    }
}
