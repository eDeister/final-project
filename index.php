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




//Run fat free
$f3->run();