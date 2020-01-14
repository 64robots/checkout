<?php

namespace R64\Checkout\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPurchase extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [];
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [];
    public $timestamps = true;
}
