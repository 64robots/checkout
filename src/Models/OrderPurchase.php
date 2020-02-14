<?php

namespace R64\Checkout\Models;

use R64\Checkout\Contracts\Customer as CustomerContract;

//extends
use Illuminate\Database\Eloquent\Model;

class OrderPurchase extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'order_data' => 'array'
    ];
    public $timestamps = true;

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function order()
    {
        return $this->belongsTo(\R64\Checkout\Facades\Order::getClassName(), \R64\Checkout\Facades\Order::getForeignKey());
    }

    /***************************************************************************************
     ** CREATE / UPDATE
     ***************************************************************************************/

    public static function makeOne(array $data, CustomerContract $customer)
    {
        $customerForeignKey = \R64\Checkout\Facades\Customer::getForeignKey();

        $purchase = new self;
        $purchase->{$customerForeignKey} = $customer->getId();
        $purchase->order_data = $data['order_data'];
        $purchase->email = $data['email'];
        $purchase->amount = $data['amount'];
        $purchase->card_type = $data['card_type'];
        $purchase->card_last4 = $data['card_last4'];
        $purchase->stripe_customer_id = $data['stripe_customer_id'];
        $purchase->stripe_card_id = $data['stripe_card_id'];
        $purchase->stripe_charge_id = $data['stripe_charge_id'];
        $purchase->stripe_fee = round($purchase->amount * config('checkout.stripe.percentage_fee')) + config('checkout.stripe.fixed_fee');
        $purchase->save();

        return $purchase;
    }

    public static function makeFreePurchase(array $data, CustomerContract $customer)
    {
        $customerForeignKey = \R64\Checkout\Facades\Customer::getForeignKey();

        $purchase = new self;
        $purchase->{$customerForeignKey} = $customer->getId();
        $purchase->order_data = $data;
        $purchase->email = !empty($data['email']) ? $data['email'] : $customer->getEmail();
        $purchase->amount = 0;
        $purchase->save();

        return $purchase;
    }
}
