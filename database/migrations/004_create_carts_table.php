<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();

            $table->integer('items_subtotal')->default(0);
            $table->integer('tax_rate')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('shipping')->default(0);
            $table->string('customer_email')->nullable();
            $table->string('shipping_first_name')->nullable();
            $table->string('shipping_last_name')->nullable();
            $table->string('shipping_address_line1')->nullable();
            $table->string('shipping_address_line2')->nullable();
            $table->string('shipping_address_city')->nullable();
            $table->string('shipping_address_region')->nullable();
            $table->string('shipping_address_zipcode')->nullable();
            $table->string('shipping_address_phone')->nullable();
            $table->boolean('billing_same')->default(true);
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_address_line1')->nullable();
            $table->string('billing_address_line2')->nullable();
            $table->string('billing_address_city')->nullable();
            $table->string('billing_address_region')->nullable();
            $table->string('billing_address_zipcode')->nullable();
            $table->string('billing_address_phone')->nullable();
            $table->text('customer_notes')->nullable();
            $table->string('token')->unique();
            $table->ipAddress('ip_address');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('carts');
    }
}
