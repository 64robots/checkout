<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use R64\Checkout\PaymentHandlerFactory;

class AddPaypalColumnsToOrderPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_purchases', function (Blueprint $table) {
            $table->string('payment_processor')->default(PaymentHandlerFactory::STRIPE);
            $table->string('paypal_order_id')->nullable();
            $table->string('paypal_authorization_id')->nullable();
            $table->string('paypal_capture_id')->nullable();
            $table->string('paypal_payer_id')->nullable();
            $table->unsignedInteger('paypal_fee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_purchases', function (Blueprint $table) {
            $table->dropColumn([
                'payment_processor',
                'paypal_order_id',
                'paypal_authorization_id',
                'paypal_capture_id',
                'paypal_payer_id',
                'paypal_fee'
            ]);
        });
    }
}
