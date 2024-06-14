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
     * The default constructor for an admin.
     *
     * @param Listing[] $cart The current contents of the Admin's cart
     * @param string $email The admin's email address
     * @param string $fname The admin's first name
     * @param string $lname The admin's last name
     */
    public function __construct($cart, $email, $fname, $lname)
    {
        parent::__construct($cart, $email, $fname, $lname);
    }
    /**
     * Removes a listing from the database
     *
     * @param string $code The code of the listing being removed.
     * @param PDO $data The database connection used to remove the listing.
     * @return void
     */
    function removeListing($code, $data)
    {
        $data->removeListingDB($code);
    }

    //TODO: The two functions below are currently non-functional

    /**
     * Adds a listing to the database
     *
     * @param Listing $listing The listing object being added.
     * @param PDO $data The database connection used to add the listing.
     * @return void
     */
    function addListing($listing, $data)
    {
        $data->addListingDB($listing);
    }

    /**
     * Updates an existing listing in the database.
     *
     * @param Listing $listing The new listing object used to overwrite the old one.
     * @param PDO $data The database connection used to overwrite the old listing.
     * @return void
     */
    function updateListing($listing, $data)
    {
        $data->updateListingDB($listing);
    }

}