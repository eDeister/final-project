<?php

/**
 * Describes a Customer on the Phrygian website.
 *
 * Describes a Customer on the Phrygian website. Customers have a cart populated with listing, an email address,
 * a first and last name, and an array of orders that they have placed. They may place an order, view their orders,
 * and update their orders (when an order is placed).
 *
 * @author Ethan Deister
 * @author Eugene Faison
 * @author Abdul Rahmani
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