<?php
// Set error reporting, require autoload, and start session.
ini_set('error_reporting',1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
session_start();

// Create instance of f3 base class and the controller
$f3 = Base::Instance();
$con = new Controller($f3);

//  === Routes for basic user interaction

// Define a default route for the home page
$f3->route('GET /', function() {
    $GLOBALS['con']->home();
});

// Define a route for about page
$f3->route('GET /about', function() {
    $GLOBALS['con']->about();
});

// Define a route for logging in
$f3->route('GET|POST /login', function() {
    $GLOBALS['con']->login();
});

// Define a route for signing up
$f3->route('GET|POST /signup', function() {
    $GLOBALS['con']->signup();
});

//  === Routes for searching and viewing listings

// Define a route for the search page
$f3->route('GET|POST /search', function() {
    $GLOBALS['con']->search();
});

// Define a route to get listings based on filters and display them as bootstrap cards
$f3->route('GET|POST /get-listings', function() {
    $GLOBALS['con']->getListings();
});

// Define a route for displaying one listing
$f3->route('GET /listing-@code', function($f3,$params) {
    $GLOBALS['con']->listing($params['code']);
});

//  === Routes for interacting with the cart and placing/viewing orders

// Define a route for adding an items to the cart via jQuery
$f3->route('POST /cart/add', function() {
    $GLOBALS['con']->cartAdd();
});

// Define a route for removing an item from the cart via jQuery
$f3->route('POST /cart/remove', function() {
    $GLOBALS['con']->cartRemove();
});

// Define a route for emptying the cart via jQuery
$f3->route('POST /cart/empty', function() {
    $GLOBALS['con']->cartEmpty();
});

// Define a route for checking out
$f3->route('GET|POST /checkout', function() {
    $GLOBALS['con']->checkout();
});

// Define a route for viewing orders
$f3->route('GET /orders', function() {
    $GLOBALS['con']->orders();
});

//  === Routes for Admin Controls
//TODO: Make sure all admin controls are functional

// Define a route to add a listing
$f3->route('POST /listing/add', function() {
    $GLOBALS['con']->listingAdd();
});

// Define a route to remove a listing
$f3->route('POST /listing/remove-@code', function($f3,$params) {
    $GLOBALS['con']->listingRemove($params['code']);
});

// Define a route to update a listing
$f3->route('POST /listing/update-@code', function($f3,$params) {
    $GLOBALS['con']->listingUpdate($params['code']);
});


// Run fat free
$f3->run();
