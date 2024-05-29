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

    public function __construct($code,$name,$brand,$price,$sale,$desc,$specs)
    {
        $this->_code = $code;
        $this->_name = $name;
        $this->_brand = $brand;
        $this->_price = $price;
        $this->_sale = $sale;
        $this->_desc = $desc;
        $this->_specs = $specs;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->_code;
    }/**
     * @param mixed $code
     */
    public function setCode($code): void
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
    public function setName($name)
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
    public function setBrand($brand)
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
     * @return mixed
     */
    public function getSale()
    {
        return $this->_sale;
    }

    /**
     * @param mixed $sale
     */
    public function setSale($sale): void
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
    public function setDesc($desc)
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
    public function setSpecs($specs)
    {
        $this->_specs = $specs;
    }

    public function addSpec($specKey,$specValue)
    {
        $this->_specs[$specKey] = $specValue;
    }



}