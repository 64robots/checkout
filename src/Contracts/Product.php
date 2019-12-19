<?php

namespace R64\Checkout\Contracts;

interface Product
{
    public function getPrice();

    public function getName();

    public function hasImage();

    public function getImageUrl();

    public function hasTaxRate();

    public function getTaxRate();

    public function hasShipping();

    public function getShipping();
}
