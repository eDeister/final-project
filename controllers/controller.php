<?php
/**
 * IGNORE: This class is not currenly in use.
 *
 */
class Controller
{
    private static $_f3;

    function __Construct($f3)
    {
        $this->_f3 = $f3;
    }

    function home()
    {
        $view = new Template();
        echo $view->render('views/home.html');
    }

    function about()
    {
        $view = new Template();
        echo $view->render('views/about.html');
    }

    function search()
    {
        //Populate an array of test/example listings;
        $testListings = DataLayer::getListings('','');

        //Set number of listings per row, and a variable for the listings
        $this->_f3->set('row',3);
        $this->_f3->set('listings', $testListings);

        $view = new Template();
        echo $view->render('views/search.html');

    }

    function listing($params)
    {
        //TODO: Query a database using $params['listing'] to
        // get a listing object and display appropriate data

        $view = new Template();
        echo $view->render('views/listing.html');

    }

    function cart()
    {
        $view = new Template();
        echo $view->render('views/cart.html');
    }

    function checkout()
    {
        $view = new Template();
        echo $view->render('views/checkout.html');
    }

}