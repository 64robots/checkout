<?php

namespace R64\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'coupons';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
    public $timestamps = true;

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function calculateDiscount(Cart $cart)
    {
        if ($this->percentage) {
            return $cart->items_subtotal * $this->discount / 100;
        }

        $discount = $this->discount;

        return $discount < $cart->items_subtotal ? $discount : $cart->items_subtotal;
    }
}
