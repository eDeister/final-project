<?php
//Set error reporting, require autoload, and start session.
ini_set('error_reporting',1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
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

//Define a route for searching/filtering/sorting listings
$f3->route('GET|POST /search', function($f3) {
    //Populate an array of test/example listings
    $testListings = array(
        new Listing('instr1','Test name 1', 'Test brand 1', 5, 'Test desc 1', array('spec1' => 'spec1', 'spec2'=>'spec2')),
        new Listing('instr2','Test name 2', 'Test brand 2', 5, 'Test desc 2', array('spec1' => 'spec1', 'spec2'=>'spec2')),
        new Listing('instr3','Test name 3', 'Test brand 3', 5, 'Test desc 3', array('spec1' => 'spec1', 'spec2'=>'spec2')),
        new Listing('instr4','Test name 4', 'Test brand 4', 5, 'Test desc 4', array('spec1' => 'spec1', 'spec2'=>'spec2')),
    );
    //Set number of listings per row, and a variable for the listings
    $f3->set('row',3);
    $f3->set('listings', $testListings);

    $view = new Template();
    echo $view->render('views/search.html');
});

//Define a route for any particular listing/instrument's page
$f3->route('GET /listing/@listing', function($f3, $params) {
    //TODO: Query a database using $params['listing'] to
    // get a listing object and display appropriate data

    $view = new Template();
    echo $view->render('views/listing.html');
});

//Run fat free
$f3->run();