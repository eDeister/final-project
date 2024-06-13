<?php
// Set error reporting, require autoload, and start session.
ini_set('error_reporting',1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
session_start();

// Create instance of f3 base class and the controller
$f3 = Base::Instance();
$con = new Controller($f3);

// Define a default route
$f3->route('GET /', function() {
    $GLOBALS['con']->home();
});

// Define a route for about page
$f3->route('GET /about', function() {
    $GLOBALS['con']->about();
});

// Define a route for any particular listing
$f3->route('GET /listing-@code', function($f3,$params) {
    $GLOBALS['con']->listing($params['code']);
});

// Define a route for the search page
$f3->route('GET|POST /search', function() {
    $GLOBALS['con']->search();
});

//TODO: Add jQuery functionality for cart

// Define routes for the cart
$f3->route('POST /cart', function() {
    $GLOBALS['con']->cart();
});

$f3->route('POST /cart/add', function() {
    $GLOBALS['con']->cartAdd();
});

$f3->route('POST /cart/remove', function() {
    $GLOBALS['con']->cartRemove();
});

$f3->route('POST /cart/empty', function() {
    $GLOBALS['con']->cartEmpty();
});

//TODO: Make sure login and signup are working and add/retrieve users to DB

// Define a route for login
$f3->route('GET|POST /login', function() {
    $GLOBALS['con']->login();
});

// Define a route for signup
$f3->route('GET|POST /signup', function() {
    $GLOBALS['con']->signup();
});

//TODO: Make sure checkout is working and orders are added to DB

// Define a route for checkout
$f3->route('GET|POST /checkout', function() {
    $GLOBALS['con']->checkout();
});

// Define a route to get listings
$f3->route('GET|POST /get-listings', function() {
    $GLOBALS['con']->getListings();
});

//== Routes for Admin Controls
//TODO: Make sure all admin controls are functional

// Define a route to add a listing
$f3->route('POST /listing/add', function() {
    $GLOBALS['con']->listingAdd();
});

// Define a route to remove a listing
$f3->route('POST /listing/remove-@code', function($params) {
    $GLOBALS['con']->listingRemove($params);
});

// Define a route to update a listing
$f3->route('POST /listing/update-@code', function($params) {
    $GLOBALS['con']->listingUpdate($params);
});

// Define a route to add a filter
$f3->route('POST /filters/add-@code', function($params) {
    $GLOBALS['con']->filterAdd($params);
});

// Define a route to remove a filter
$f3->route('POST /filters/remove-@code', function($params) {
    $GLOBALS['con']->filterRemove($params);
});

// Run fat free
$f3->run();
