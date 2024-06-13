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

    public function __construct($cart,$email,$fname,$lname)
    {
        $this->_cart = $cart;
        $this->_email = $email;
        $this->_fname = $fname;
        $this->_lname = $lname;
    }

    public function getCart()
    {
        return $this->_cart;
    }
    public function getEmail()
    {
        return $this->_email;
    }
    public function getFname()
    {
        return $this->_fname;
    }
    public function getLname()
    {
        return $this->_lname;
    }

    public function setCart($cart)
    {
        $this->_cart = $cart;
    }
    public function setEmail($email)
    {
        $this->_email = $email;
    }
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }
}