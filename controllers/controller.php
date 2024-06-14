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

    function listing($code)
    {
        $filters = ['code' => $code];
        $listing = $this->_data->getListings($filters);
        $this->_f3->set('listing', $listing[$code]);

        $view = new Template();
        echo $view->render('views/listing.html');

    }

    function cartAdd()
    {
        // Fetch cart items from session
        $cart = $this->_f3->get('SESSION.user')->getCart();
        $code = $_POST['code'];
        $listing = $this->_data->getListings(['code'=>$code]);
        $cart[$code] = $listing[$code];
        $this->_f3->get('SESSION.user')->setCart($cart);
        $view = new Template();
        echo $view->render('views/includes/cart.html');
    }

    function cartRemove()
    {
        // Fetch cart items from session
        $cart = $this->_f3->get('SESSION.user')->getCart();
        $code = $_POST['code'];
        unset($cart[$code]);
        $this->_f3->get('SESSION.user')->setCart($cart);
        $view = new Template();
        echo $view->render('views/cart.html');
    }

    function cartEmpty()
    {
        // Empty the cart (session)
        $this->_f3->get('SESSION.user')->setCart(null);
    }

    function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //TODO: Validate data using datalayer
            // Make sure that the data gets sent to DB
            $email = $_POST['email'];
            $password = $_POST['password'];
            $error = false;

            if (!$this->_data->validEmail($email)) {
                $this->_f3->set('emailError', 'Email is invalid.');
                $error = true;
            }
            if (!$this->_data->validPassword($password)) {
                $this->_f3->set('passError', 'Password is invalid.');
                $error = true;
            }

            if (!$error) {
                $user = $this->_data->getUserByEmail($email);

                if (password_verify($password, $user['password'])) {
                    $cart = $this->_f3->get('SESSION.cart');
                    if ($user['isAdmin'] == 1) {
                        $this->_f3->set('SESSION.user', new Admin($cart,$user['email'],$user['fname'],$user['lname']));
                    } else {
                        $this->_f3->set('SESSION.user', new Customer($cart,$user['email'],$user['fname'],$user['lname']));
                    }
                    $this->_f3->reroute('/');
                } else {
                    $this->_f3->set('passIncorrectError', 'Incorrect password.');
                }
            }
        }
        $view = new Template();
        echo $view->render('views/login.html');
    }

    function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //TODO: Make sure that the data gets sent to DB
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $error = false;

            if (!$this->_data->validName($firstName)) {
                $this->_f3->set('fnameError', 'First name is invalid.');
                $error = true;
            }
            if (!$this->_data->validName($lastName)) {
                $this->_f3->set('lnameError', 'Last name is invalid');
                $error = true;
            }
            if (!$this->_data->validEmail($email)) {
                $this->_f3->set('emailError', 'Email is invalid');
                $error = true;
            }
            if (!$this->_data->validPassword($password)) {
                $this->_f3->set('passError', 'Password is invalid');
                $error = true;
            }

            if (!$error) {
                $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $this->_data->createUser($firstName, $lastName, $email, $passwordHashed);
                $this->_f3->reroute('login');
            }
        }
        $view = new Template();
        echo $view->render('views/signup.html');
    }

    function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->_f3->get('SESSION.user');
            $this->_f3->set('posted',true);
            $error = false;

            if (empty($user)) {
                $this->_f3->set('userError','Error processing order: User not logged in');
                $error = true;
            }
            if (empty($user->getCart())) {
                $this->_f3->set('cartError','Error processing order: Empty cart');
                $error = true;
            }

            if (!$error) {
                $user->placeOrder($this->_data);
                $this->_f3->get('SESSION.user')->setCart(null);
            }
        } else {
            if (empty($this->_f3->get('SESSION.user'))) {
                $this->_f3->reroute('login');
            } else if (!empty($this->_f3->get('SESSION.user')->getCart())) {
                $cart = $this->_f3->get('SESSION.user')->getCart();
                $total = 0;
                foreach ($cart as $listing) {
                    $total += $listing->getPrice() * $listing->getSale();
                }
                $this->_f3->set('total', $total);
            }
        }
        $view = new Template();
        echo $view->render('views/checkout.html');
    }

    function listingAdd()
    {
//        $code = $_POST['code'];
//        $name = $_POST['name'];
//        $brand = $_POST['brand'];
//        $price = $_POST['price'];
//        $sale = $_POST['sale'];
//        $type = $_POST['type'];
//        $listing = new Listing($code,$name,$brand,$price,null,$sale,null,$type);
//        $this->_f3->get('SESSION.user')->addListing($listing, $this->_data);
//        $view = new Template;
//        echo $view->render('views/search.html');
    }

    function listingRemove($code)
    {
        $this->_f3->get('SESSION.user')->removeListing($code, $this->_data);
    }

    function listingUpdate()
    {
        $view = new Template;
        echo $view->render('views/includes/get-listings.html');
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