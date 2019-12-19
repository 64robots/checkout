<?php

namespace R64\Checkout;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use R64\Checkout\Models\Cart;
use R64\Checkout\Models\CartItem;
use R64\Checkout\Models\CheckoutProduct;
use R64\Checkout\Models\Coupon;
use R64\Checkout\Models\Order;
use R64\Checkout\Models\OrderItem;

class CheckoutAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Cart::class => App\Policies\CartPolicy::class,
        CartItem::class => App\Policies\CartItemPolicy::class,
        CheckoutProduct::class => App\Policies\CheckoutProductPolicy::class,
        Coupon::class => App\Policies\CouponPolicy::class,
        Order::class => App\Policies\OrderPolicy::class,
        OrderItem::class => App\Policies\OrderItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}