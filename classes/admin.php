<?php


/**
 * Describes an admin on Phrygian.
 *
 * Describes an admin on Phrygian. Admins are capable of adding items to their cart for testing purposes. Admins are
 * also capable of removing listings from the database.
 *
 * @author Ethan Deister
 * @author Eugene Faison
 * @author Abdul Rahmani
 */
class Admin extends User
{

    /**
     *
     *
     * @param $cart
     * @param $email
     * @param $fname
     * @param $lname
     */
    public function __construct($cart, $email, $fname, $lname)
    {
        parent::__construct($cart, $email, $fname, $lname);
    }
    /**
     * @param $listing
     * @param $data
     * @return void
     */
    function removeListing($listing, $data)
    {
        $data->removeListingDB($listing);
    }

    //TODO: The two functions below are currently non-functional

    /**
     * @param $code
     * @param $data
     * @return void
     */
    function addListing($code, $data)
    {
        $data->addListingDB($code);
    }

    /**
     * @param $listing
     * @param $dbh
     * @return void
     */
    function updateListing($listing, $data)
    {
        $data->updateListingDB($listing);
    }

}