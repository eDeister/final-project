<?php


class Admin extends User
{

    public function __construct($cart, $email, $fname, $lname)
    {
        parent::__construct($cart, $email, $fname, $lname);
    }

    function addListing($listing, $data)
    {
        $data->addListingDB($listing);
    }

    function removeListing($listing, $data)
    {
        $data->removeListingDB($listing);
    }

    function updateListing($listing, $dbh)
    {

    }

    function addSpecFilter($specKey, $dbh)
    {

    }

    function removeSpecFilter($specKey, $dbh)
    {

    }

}