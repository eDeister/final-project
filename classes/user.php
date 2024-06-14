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
     * Defines the default constructor for the User.
     *
     * @param Listing[] $cart The user's current cart contents
     * @param string $email The user's email address
     * @param string $fname The user's first name
     * @param string $lname The user's last name
     */
    public function __construct($cart, $email, $fname, $lname)
    {
        $this->_cart = $cart;
        $this->_email = $email;
        $this->_fname = $fname;
        $this->_lname = $lname;
    }

    /**
     * @return Listing[] Return the user's current cart.
     */
    public function getCart()
    {
        return $this->_cart;
    }

    /**
     * @return string Return the user's email address.
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return string Return the user's first name.
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * @return string Return the user's last name.
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * @param Listing[] $cart Set the user's cart contents.
     * @return void
     */
    public function setCart($cart)
    {
        $this->_cart = $cart;
    }

    /**
     * @param string $email Set the user's email.
     * @return void
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @param string $fname Set the user's first name.
     * @return void
     */
    public function setFname($fname)
    {
        $this->_fname = $fname;
    }

    /**
     * @param string $lname Set the uer's last name.
     * @return void
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }
}