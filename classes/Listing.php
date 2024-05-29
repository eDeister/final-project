<?php

class Listing
{
    private $_code;
    private $_name;
    private $_brand;
    private $_price;
    private $_sale;
    private $_desc;
    private $_specs;

    public function __construct($code,$name,$brand,$price,$desc,$sale=1,$specs=array())
    {
        $this->_code = $code;
        $this->_name = $name;
        $this->_brand = $brand;
        $this->_price = $price;
        $this->_desc = $desc;
        $this->_sale = $sale;
        $this->_specs = $specs;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }/**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->_code = $code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->_brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand)
    {
        $this->_brand = $brand;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    /**
     * @return float
     */
    public function getSale()
    {
        return $this->_sale;
    }

    /**
     * @param float $sale
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

    public function addSpec($specKey,$specValue)
    {
        $this->_specs[$specKey] = $specValue;
    }



}