<?php

namespace R64\Checkout\Models;

use R64\Checkout\Contracts\Customer as CustomerContract;

//extends
use Illuminate\Database\Eloquent\Model;
use R64\Checkout\PaymentHandlerFactory;

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
        $purchase->payment_processor = $data['payment_processor'];

        if ($data['payment_processor'] === PaymentHandlerFactory::STRIPE) {
            $purchase->card_type = $data['card_type'];
            $purchase->card_last4 = $data['card_last4'];
            $purchase->stripe_customer_id = $data['stripe_customer_id'];
            $purchase->stripe_card_id = $data['stripe_card_id'];
            $purchase->stripe_charge_id = $data['stripe_charge_id'];
            $purchase->stripe_fee = round($purchase->amount * config('checkout.stripe.percentage_fee')) + config('checkout.stripe.fixed_fee');
        } elseif ($data['payment_processor'] === PaymentHandlerFactory::PAYPAL) {
            $purchase->paypal_order_id = $data['paypal_order_id'];
            $purchase->paypal_authorization_id = $data['paypal_authorization_id'];
            $purchase->paypal_capture_id = $data['paypal_capture_id'];
            $purchase->paypal_payer_id = $data['paypal_payer_id'];
            $purchase->paypal_fee = $data['paypal_fee'];
        }

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

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/
    public function scopeStripe($query)
    {
        return $query->where('payment_processor', PaymentHandlerFactory::STRIPE);
    }

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
