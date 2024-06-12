<?php
// Set error reporting, require autoload, and start session.
ini_set('error_reporting',1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
require_once('controllers/controller.php');
session_start();

// Create instance of f3 base class
$f3 = Base::Instance();

// Define a default route
$f3->route('GET /', function() {
    $view = new Template();
    echo $view->render('views/home.html');
});

// Define a route for about page
$f3->route('GET /about', function() {
    $view = new Template();
    echo $view->render('views/about.html');
});

// Define a route for any particular listing
$f3->route('GET /listing-@code', function($f3, $params) {
    $dataLayer = new DataLayer();
    $filters = ['code' => $params['code']];
    $listing = $dataLayer->getListings($filters)[$params['code']];
    $f3->set('listing', $listing);


    $view = new Template();
    echo $view->render('views/listing.html');
});

$f3->route('GET|POST /search', function($f3) {
    $dataLayer = new DataLayer();
    $results = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filters = [];
        if (!empty($_POST['query'])) {
            $filters['name'] = $_POST['query'];
        }
        if (!empty($_POST['filter'])) {
            $filters['type'] = $_POST['filter'];
        }
        $results = $dataLayer->getListings($filters);
    } else {
        $results = $dataLayer->getListings();
    }

    $f3->set('filterList', $dataLayer->getFilters());
    $f3->set('sortList', $dataLayer->getSorts());
    $f3->set('row', 3);
    $f3->set('results', $results);
    $view = new Template();
    echo $view->render('views/search.html');
});

// Define a route for the cart
$f3->route('GET /cart', function($f3) {
    // Fetch cart items from session
    $cartItems = isset($_SESSION['cartItems']) ? $_SESSION['cartItems'] : [];
    $f3->set('cartItems', $cartItems);
    $view = new Template();
    echo $view->render('views/cart.html');
});

$f3->route('POST /cart/add', function($f3) {
    $itemId = $_POST['id'];
    // Add item to cart (session)
    if (!isset($_SESSION['cartItems'])) {
        $_SESSION['cartItems'] = [];
    }
    $_SESSION['cartItems'][] = $itemId;

    // Redirect to cart page
    $f3->reroute('/cart');
});

$f3->route('POST /cart/remove', function($f3) {
    $itemId = $_POST['id'];
    // Remove item from cart (session)
    if (isset($_SESSION['cartItems'])) {
        $index = array_search($itemId, $_SESSION['cartItems']);
        if ($index !== false) {
            unset($_SESSION['cartItems'][$index]);
        }
    }

    // Redirect to cart page
    $f3->reroute('/cart');
});

$f3->route('POST /cart/empty', function($f3) {
    // Empty the cart (session)
    unset($_SESSION['cartItems']);

    // Redirect to cart page
    $f3->reroute('/cart');
});

<<<<<<< HEAD
// Define a route for login
$f3->route('GET|POST /login', function($f3) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
=======
$f3->route('GET /checkout', function($f3) {
    //$f3->set('SESSION.cart', DataLayer :: getListings());

    // Redirect to cart page or previous page
    $view = new Template();
    echo $view->render('views/checkout.html');
});

>>>>>>> 42c4aa7c9e628cac7ba5228b732376cb9c3e0c3d

        $user = DataLayer::getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $f3->reroute('/');
        } else {
            $f3->set('error', 'Invalid email or password');
        }
    }
    $view = new Template();
    echo $view->render('views/login.html');
});

// Define a route for signup
$f3->route('GET|POST /signup', function($f3) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        if (DataLayer::createUser($firstName, $lastName, $email, $password)) {
            $f3->reroute('/login');
        } else {
            $f3->set('error', 'Unable to create account');
        }
    }
    $view = new Template();
    echo $view->render('views/signup.html');
});

// Run fat free
$f3->run();
