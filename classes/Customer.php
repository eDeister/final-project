<?php

class Customer extends User
{

    public function __construct($cart, $email, $fname, $lname)
    {
        parent::__construct($cart, $email, $fname, $lname);
    }

    function placeOrder($data)
    {
        $cart = $this->getCart();
        $data->placeOrder($this->getEmail(),$cart);
    }
}