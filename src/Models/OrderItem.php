<?php
namespace R64\Checkout\Models;

use Illuminate\Support\Arr;

// extends
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
    public $timestamps = true;

    use SoftDeletes;

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function product()
    {
        return $this->belongsTo(\R64\Checkout\Facades\Product::getClassName(), \R64\Checkout\Facades\Product::getForeignKey());
    }

    public function cartItem()
    {
        return $this->belongsTo(\R64\Checkout\Facades\CartItem::getClassName(), \R64\Checkout\Facades\CartItem::getForeignKey());
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(array $data)
    {
        $orderItem = new self;

        $productForeignKey = \R64\Checkout\Facades\Product::getForeignKey();
        $cartItemForeignKey = \R64\Checkout\Facades\CartItem::getForeignKey();

        $orderItem->order_id = Arr::get($data, 'order_id');
        $orderItem->{$productForeignKey} = Arr::get($data, $productForeignKey);
        $orderItem->{$cartItemForeignKey} = Arr::get($data, $cartItemForeignKey);
        $orderItem->price = Arr::get($data, 'price');
        $orderItem->quantity = Arr::get($data, 'quantity');
        $orderItem->name = Arr::get($data, 'name');
        $orderItem->customer_note = Arr::get($data, 'customer_note');
        $orderItem->save();

        return $orderItem;
    }

    public static function makeOneFromCartItem(CartItem $cartItem, $orderId)
    {
        $product = $cartItem->product;

        $productForeignKey = \R64\Checkout\Facades\Product::getForeignKey();
        $orderForeignKey = \R64\Checkout\Facades\Order::getForeignKey();
        $cartItemForeignKey = \R64\Checkout\Facades\CartItem::getForeignKey();

        $orderItem = static::makeOne([
            $orderForeignKey => $orderId,
            $productForeignKey => $product->id,
            $cartItemForeignKey => $cartItem->id,
            'price' => $cartItem->price,
            'quantity' => $cartItem->quantity,
            'name' => $product->getName(),
            'customer_note' => $cartItem->customer_note
        ]);

        return $orderItem;
    }
}
