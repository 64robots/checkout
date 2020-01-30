<?php

namespace R64\Checkout;

use R64\Checkout\Contracts\Customer;

class GuestCustomer implements Customer
{
    private $email;

    private $firstName;

    private $lastName;

    public function __construct($email, $firstName, $lastName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function getId()
    {
        return null;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
