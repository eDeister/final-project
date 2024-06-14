<?php

/**
 * Describes a class that represents a single user on our website.
 *
 * Describes a class that represents a single user on our website. The user can either be a Customer or Admin. Both
 * have a cart (admins too, for testing purposes), an email, a first name, and a last name.
 */
class User
{
    private $_cart;
    private $_email;
    private $_fname;
    private $_lname;

    /**
     * @param $cart
     * @param $email
     * @param $fname
     * @param $lname
     */
    public function __construct($cart, $email, $fname, $lname)
    {
        $this->_cart = $cart;
        $this->_email = $email;
        $this->_fname = $fname;
        $this->_lname = $lname;
    }

    /**
     * @return mixed
     */
    public function getCart()
    {
        return $this->_cart;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return mixed
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * @return mixed
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * @param $cart
     * @return void
     */
    public function setCart($cart)
    {
        $this->_cart = $cart;
    }

    /**
     * @param $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @param $fname
     * @return void
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * @param $lname
     * @return void
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }
}