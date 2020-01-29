<?php

namespace R64\Checkout\Contracts;

interface State
{
    public function all();

    public function getByCode($stateCode);
}
