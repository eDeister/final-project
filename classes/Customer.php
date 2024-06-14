<?php

class Customer extends User
{
    private $_orders;

    public function __construct($cart, $email, $fname, $lname, $orders='')
    {
        parent::__construct($cart, $email, $fname, $lname);
        $this->_orders = $orders;
    }

    function placeOrder($data)
    {
        $cart = $this->getCart();
        $data->placeOrder($this->getEmail(),$cart);
    }

    function getOrders()
    {
        return $this->_orders;
    }

    function setOrders($orders)
    {
        $this->_orders = $orders;
    }
}