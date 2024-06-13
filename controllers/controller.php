<?php
/**
 *
 *
 */
class Controller
{
    private $_f3;
    private $_data;

    function __construct($f3)
    {
        $this->_f3 = $f3;
        $this->_data = new DataLayer();
    }

    function home()
    {
        //TODO: Display four sale items on home screen
        $filters = [
            'sale' => 1,
            'limit' => 4
        ];
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $filters = [];
            if (!empty($_POST['query'])) {
                $filters['name'] = $_POST['query'];
            }
            $results = $this->_data->getListings($filters);
            if (!empty($_POST['sort'])) {
                if($_POST['sort'] == 'price-asc') {
                    usort($results, function($a,$b) {
                        return $a->getPrice()*$a->getSale() <=> $b->getPrice()*$b->getSale();
                    });
                } else if ($_POST['sort'] == 'price-desc') {
                    usort($results, function($a,$b) {
                        return $b->getPrice()*$b->getSale() <=> $a->getPrice()*$a->getSale();
                    });
                } else if ($_POST['sort'] == 'new-arrivals') {
                    usort($results, function($a,$b) {
                        return $a->getTimestamp() <=> $b->getTimestamp();
                    });
                }
            }
        } else {
            $results = $this->_data->getListings();
        }

        $this->_f3->set('filterList', $this->_data->getFilters());
        $this->_f3->set('sortList', $this->_data->getSorts());
        //TODO: Set rows responsively
        $this->_f3->set('row', 3);
        $this->_f3->set('results', $results);
        $view = new Template();
        echo $view->render('views/search.html');
    }

    function listing($params)
    {
        $code = $params['code'];
        $filters = ['code' => $code];
        $listing = $this->_data->getListings($filters);
        $this->_f3->set('listing', $listing[$code]);
        echo var_dump($listing);

        $view = new Template();
        echo $view->render('views/listing.html');

    }

    function cart()
    {
        // Fetch cart items from session
        $cartItems = $this->_f3->get('SESSION.cart');
        $code = $_POST['code'];
        $listing = $this->_data->getListings($code);
        $cartItems[$listing[$code]->getCode()] = $listing[$code];
        $this->_f3->set('cartItems', $cartItems);
        $view = new Template();
        echo $view->render('views/cart.html');
    }

    function cartAdd()
    {
        $itemId = $_POST['id'];
        // Add item to cart (session)
        if (!isset($_SESSION['cartItems'])) {
            $_SESSION['cartItems'] = [];
        }
        $_SESSION['cartItems'][] = $itemId;

        // Redirect to cart page
        $this->_f3->reroute('/cart');
    }

    function cartRemove()
    {
        $itemId = $_POST['id'];
        // Remove item from cart (session)
        if (isset($_SESSION['cartItems'])) {
            $index = array_search($itemId, $_SESSION['cartItems']);
            if ($index !== false) {
                unset($_SESSION['cartItems'][$index]);
            }
        }

        // Redirect to cart page
        $this->_f3->reroute('/cart');
    }

    function cartEmpty()
    {
        // Empty the cart (session)
        $this->_f3->clear('SESSION.cart');

        // Redirect to cart page
        $this->_f3->reroute('/cart');
    }

    function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->_data->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $cart = $this->_f3->get('cart');
                if ($user['isAdmin'] == 1) {
                    $this->_f3->set('SESSION.user', new Admin($cart,$user['email'],$user['fname'],$user['lname']));
                } else {
                    $this->_f3->set('SESSION.user', new Customer($cart,$user['email'],$user['fname'],$user['lname']));
                }
                $this->_f3->reroute('/');
            } else {
                $this->_f3->set('error', 'Invalid email or password');
            }
        }
        $view = new Template();
        echo $view->render('views/login.html');
    }

    function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($this->_data->createUser($firstName, $lastName, $email, $password)) {
                $this->_f3->reroute('/login');
            } else {
                $this->_f3->set('error', 'Unable to create account');
            }
        }
        $view = new Template();
        echo $view->render('views/signup.html');
    }

    function checkout()
    {
        $view = new Template();
        echo $view->render('views/checkout.html');
    }

    function listingAdd()
    {
        $code = $_POST['code'];
        $name = $_POST['name'];
        $brand = $_POST['brand'];
        $price = $_POST['price'];
        $sale = $_POST['sale'];
        $type = $_POST['type'];
        $listing = new Listing($code,$name,$brand,$price,null,$sale,null,$type);
        $this->_f3->get('SESSION.user')->addListing($listing, $this->_data);
        $view = new Template;
        echo $view->render('views/search.html');
    }

    function removeListing($params)
    {
        //TODO: Uncomment when finished
//        $this->_f3->get('SESSION.user')->removeListing($params['code'], $this->_data);
    }

    function listingUpdate()
    {
        $view = new Template;
        echo $view->render('views/includes/get-listings.html');
    }

    function filterAdd()
    {

    }

    function filterRemove()
    {

    }

    function getListings()
    {
        $results = $this->_data->getListings();
        $this->_f3->set('results', $results);
        $this->_f3->set('row', 3);
        $view = new Template();
        echo $view->render('views/includes/get-listings.html');
    }
}