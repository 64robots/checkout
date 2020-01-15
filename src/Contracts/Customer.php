<?php

namespace R64\Checkout\Contracts;

interface Customer
{
    public function getId();

    public function getFirstName();

    public function getLastName();

    public function getEmail();
}
