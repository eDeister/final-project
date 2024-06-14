<?php

class Order
{
    private $_listings;
    private $_timestamp;

    public function __construct($listings, $timestamp)
    {
        $this->_listings = $listings;
        $this->_timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getListings()
    {
        return $this->_listings;
    }

    /**
     * @param mixed $listings
     */
    public function setListings($listings)
    {
        $this->_listings = $listings;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->_timestamp = $timestamp;
    }
}