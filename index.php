<?php
//Get config, set error reporting, require autoload, and start session.
//require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';
ini_set('error_reporting',1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
require_once('controllers/controller.php');
session_start();

//Create instance of f3 base class
$f3 = Base::Instance();


//Define a default route
$f3->route('GET /', function() {
    $view = new Template();
    echo $view->render('views/home.html');
});


//Define a route for about page
$f3->route('GET /about', function() {
    $view = new Template();
    echo $view->render('views/about.html');
});


//Define a route for any particular listing
$f3->route('GET /listing-@code', function($f3, $params) {
    //Use data layer to query the database using $params['listing'] to get the listing data by its code.
    $code = $params['code'];
    $filters = array(
        'code' => $code
    );
    $listing = DataLayer::getListings($filters)[$code];
    $f3->set('listing', $listing);


    $view = new Template();
    echo $view->render('views/listing.html');
});


$f3->route('GET|POST /search', function($f3) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $query = $_POST['query'];
        $filter = $_POST['filter'];
        // TODO: Implement DataLayer query with filters
    } else {
        $results = DataLayer::getListings(null);
    }
    $f3->set('filterList', DataLayer::getFilters());
    $f3->set('sortList',DataLayer::getSorts());
    $f3->set('row', 3);
    $f3->set('results', $results);
    $view = new Template();
    echo $view->render('views/search.html');
});



$f3->route('GET /cart', function($f3) {
    // TODO: Implement logic to fetch cart items
    $cartItems = []; // This should be fetched from session or database

    $f3->set('cartItems', $cartItems);
    $view = new Template();
    echo $view->render('views/cart.html');
});

$f3->route('POST /cart/add', function($f3) {
    $itemId = $_POST['id'];
    // TODO: Implement logic to add item to cart

    // Redirect to cart page or previous page
    $f3->reroute('/cart');
});

$f3->route('POST /cart/remove', function($f3) {
    $itemId = $_POST['id'];
    // TODO: Implement logic to remove item from cart

    // Redirect to cart page or previous page
    $f3->reroute('/cart');
});

$f3->route('POST /cart/empty', function($f3) {
    // TODO: Implement logic to empty the cart

    // Redirect to cart page or previous page
    $f3->reroute('/cart');
});

$f3->route('GET /checkout', function($f3) {
    //$f3->set('SESSION.cart', DataLayer :: getListings());

    // Redirect to cart page or previous page
    $view = new Template();
    echo $view->render('views/checkout.html');
});


//Run fat free
$f3->run();