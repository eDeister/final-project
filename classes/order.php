<?php

/**
 * Represents an order placed by a Customer on the Phrygian website.
 *
 * Represents an order place by a Customer on the Phrygian website. Complete with a timestamp and an array of the
 * instrument listings bought in the order.
 *
 * @author Ethan Deister
 * @author Eugene Faison
 * @author Abdul Rahmani
 */
class Order
{
    private $_listings;
    private $_timestamp;

    /**
     * The default constructor for an order
     *
     * @param Listing[] $listings The listings bought in this order.
     * @param string $timestamp The timestamp when this order was placed.
     */
    public function __construct($listings, $timestamp)
    {
        $this->_listings = $listings;
        $this->_timestamp = $timestamp;
    }

    /**
     * @return mixed Return the array of listings purchased.
     */
    public function getListings()
    {
        return $this->_listings;
    }

    /**
     * @param mixed $listings Set the array of listings purchased.
     */
    public function setListings($listings)
    {
        $this->_listings = $listings;
    }

    /**
     * @return mixed Get the timestamp when the order was placed.
     */
    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    /**
     * @param mixed $timestamp Set the timestamp when the order was placed.
     */
    public function setTimestamp($timestamp)
    {
        $this->_timestamp = $timestamp;
    }
}