<?php

namespace R64\Checkout;

use R64\Checkout\Models\Product;

class ProductRepository
{
    /** @var Product */
    private $model;

    /**
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * @return Product
     */
    public function getModel()
    {
        return $this->model;
    }
}
