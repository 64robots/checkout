<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');

            $table->unsignedBigInteger('customer_id')->nullable();

            $table->string('card_holder_name');
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_card_id')->nullable();
            $table->integer('amount');
            $table->integer('stripe_fee')->default(0);
            $table->integer('refunded_amount')->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_purchases');
    }
}
