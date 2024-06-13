<?php

/**
 * Describes a class that represents a single listing on a music ecommerce website.
 *
 * Describes a class that represents a single listing of an instrument on a music ecommerce website.
 * Includes basic info such as name, price, brand, specs, etc. Contains functions for getting and setting
 * attributes.
 *
 * @author Ethan Deister <deister.ethan@student.greenriver.edu>
 * @author Eugene Faison
 * @author Abdul Rahmani
 *
 */
class Listing
{
    private $_code;
    private $_name;
    private $_brand;
    private $_price;
    private $_sale;
    private $_desc;
    private $_specs;
    private $_type;
    private $_timestamp;

    /**
     * Constructs a new Listing with all data values set.
     *
     * @param string $code A code used as a url token in the controller
     * @param string $name The name of the instrument
     * @param string $brand The brand of the instrument
     * @param float $price The price of the instrument
     * @param string $desc The description of the instrument
     * @param float $sale The decimal value of the percentage off the price of the instrument
     * @param array $specs An array of the specifications of the instrument
     */
    public function __construct($code, $name, $brand, $price, $desc, $sale=1, $specs=array(), $type, $timestamp)
    {
        $this->_code = $code;
        $this->_name = $name;
        $this->_brand = $brand;
        $this->_price = $price;
        $this->_desc = $desc;
        $this->_sale = $sale;
        $this->_specs = $specs;
        $this->_type = $type;
        $this->_timestamp = $timestamp;
    }

    /**
     * @return string Returns the listing code
     */
    public function getCode()
    {
        return $this->_code;
    }/**
     * @param string $code Sets the listing code
     */
    public function setCode(string $code)
    {
        $this->_code = $code;
    }

    /**
     * @return string Returns the instrument/listing name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name Sets the listing name
     */
    public function setName(string $name)
    {
        $this->_name = $name;
    }

    /**
     * @return string Returns the brand name
     */
    public function getBrand()
    {
        return $this->_brand;
    }

    /**
     * @param string $brand Sets the brand name
     */
    public function setBrand(string $brand)
    {
        $this->_brand = $brand;
    }

    /**
     * @return float Returns the listing price
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param float $price Sets the listing price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function getAbsolutePrice()
    {
        return number_format(($this->_price * $this->_sale),2);
    }

    /**
     * @return float Returns the sale percentage off
     */
    public function getSale()
    {
        return $this->_sale;
    }

    /**
     * @param float $sale Sets the sale percentage off
     */
    public function setSale(float $sale)
    {
        $this->_sale = $sale;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->_desc;
    }

    /**
     * @param string $desc
     */
    public function setDesc(string $desc)
    {
        $this->_desc = $desc;
    }

    /**
     * @return array
     */
    public function getSpecs()
    {
        return $this->_specs;
    }

    /**
     * @param array $specs
     */
    public function setSpecs(array $specs)
    {
        $this->_specs = $specs;
    }

    /**
     * @param $specKey
     * @param $specValue
     * @return void
     */
    public function addSpec($specKey, $specValue)
    {
        $this->_specs[$specKey] = $specValue;
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setType($type)
    {
        $this->_type = $type;
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