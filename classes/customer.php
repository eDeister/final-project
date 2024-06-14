<?php

/**
 *
 */
class Customer extends User
{
    private $_orders;

    /**
     * @param $cart
     * @param $email
     * @param $fname
     * @param $lname
     * @param $orders
     */
    public function __construct($cart, $email, $fname, $lname, $orders='')
    {
        parent::__construct($cart, $email, $fname, $lname);
        $this->_orders = $orders;
    }

    /**
     * @param $data
     * @return void
     */
    function placeOrder($data)
    {
        $cart = $this->getCart();
        $data->placeOrder($this->getEmail(),$cart);
    }

    /**
     * @return mixed|string
     */
    function getOrders()
    {
        return $this->_orders;
    }

    /**
     * @param $orders
     * @return void
     */
    function setOrders($orders)
    {
        $this->_orders = $orders;
    }
}