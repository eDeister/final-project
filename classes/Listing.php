<?php

class Listing
{
    private $_name;
    private $_brand;
    private $_price;
    private $_desc;
    private $_specs;

    public function __construct($name,$brand,$price,$desc,$specs)
    {
        $this->_name = $name;
        $this->_brand = $brand;
        $this->_price = $price;
        $this->_desc = $desc;
        $this->_specs = $specs;
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



}