<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('cart_item_id')->nullable();
            $table->integer('price');
            $table->integer('quantity');
            $table->string('name');
            $table->string('customer_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('cart_item_id')->references('id')->on('cart_items');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
