<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cart_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();

            $table->string('order_number')->nullable();
            $table->integer('items_total');
            $table->unsignedInteger('shipping');
            $table->integer('tax');
            $table->integer('total');
            $table->integer('tax_rate')->nullable();
            $table->unsignedInteger('discount')->default(0);
            $table->string('customer_email');
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_address_line1')->nullable();
            $table->string('shipping_address_line2')->nullable();
            $table->string('shipping_address_city')->nullable();
            $table->string('shipping_address_region')->nullable();
            $table->string('shipping_address_zipcode')->nullable();
            $table->string('shipping_address_phone')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_address_line1')->nullable();
            $table->string('billing_address_line2')->nullable();
            $table->string('billing_address_city')->nullable();
            $table->string('billing_address_region')->nullable();
            $table->string('billing_address_zipcode')->nullable();
            $table->string('billing_address_phone')->nullable();
            $table->string('status')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('token')->unique();
            $table->string('currency');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cart_id')->references('id')->on('carts');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('coupon_id')->references('id')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
