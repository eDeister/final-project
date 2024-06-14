<?php
/**
 * Routes all URLs on the Phrygian website.
 *
 * Defines a class that handles all routing and business logic as to not bloat the index page.
 *
 * @author Ethan Deister
 * @author Eugene Faison
 * @author Abdul Rahmani
 */
class Controller
{
    private $_f3;
    private $_data;

    /**
     * Defines a constructor for the controller. Accepts an instance of the f3 base class to allow routing.
     *
     * @param Base $f3 Said instance of the base class
     */
    function __construct($f3)
    {
        $this->_f3 = $f3;
        $this->_data = new DataLayer();
    }

    /**
     * Defines a route for the home page.
     *
     * @return void
     * @throws Exception
     */
    function home()
    {
        //TODO: Display four sale items on home screen
        $filters = [
            'sale' => 1,
            'limit' => 4
        ];
        //Render the about page
        $view = new Template();
        echo $view->render('views/home.html');
    }

    /**
     * Defines a route for the about page.
     *
     * @return void
     * @throws Exception
     */
    function about()
    {
        //Render the about page
        $view = new Template();
        echo $view->render('views/about.html');
    }

    /**
     * Defines a route for the login page. Validates data using functions defined in the data layer.
     *
     * @return void
     * @throws Exception
     */
    function login()
    {
        //If the user has attempted to login...
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Gather login info, set errors to off
            $email = $_POST['email'];
            $password = $_POST['password'];
            $error = false;

            //Guard clauses for invalid data
            if (!$this->_data->validEmail($email)) {
                $this->_f3->set('emailError', 'Email is invalid.');
                $error = true;
            }
            if (!$this->_data->validPassword($password)) {
                $this->_f3->set('passError', 'Password is invalid.');
                $error = true;
            }

            //If there aren't any errors...
            if (!$error) {

                //Query the DB for the user based on their email
                $user = $this->_data->getUserByEmail($email);

                //If their password matches...
                if (password_verify($password, $user['password'])) {

                    //Determine if the user is an Admin or a Customer

                    //If Admin,
                    if ($user['isAdmin'] == 1) {

                        //Initialize the user as an Admin
                        $this->_f3->set('SESSION.user', new Admin(
                                null,
                                $user['email'],
                                $user['fname'],
                                $user['lname'])
                        );
                    //Otherwise... (user is customer)
                    } else {

                        //Initialize the user as a Customer
                        $this->_f3->set('SESSION.user', new Customer(
                                null,
                                $user['email'],
                                $user['fname'],
                                $user['lname'])
                        );
                    }

                    //Reroute to homepage
                    $this->_f3->reroute('/');
                //Otherwise... (password does not match)
                } else {
                    //Set an incorrect password error
                    $this->_f3->set('passIncorrectError', 'Incorrect password.');
                }
            }
        }
        //Render the page
        $view = new Template();
        echo $view->render('views/login.html');
    }

    /**
     * Define a route for the signup page. Validates data using functions defined in the data layer.
     *
     * @return void
     * @throws Exception
     */
    function signup()
    {
        //If the user has tried signing in already...
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get user data, set errors to off
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $error = false;

            //Guard clauses for invalid data
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

            //If there aren't any errors...
            if (!$error) {
                //TODO: Add pass hint for pass reqs
                //Encrypt the chosen password, then create a user in the data base
                $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $this->_data->createUser($firstName, $lastName, $email, $passwordHashed);

                //Reroute to the login page
                $this->_f3->reroute('login');
            }
        }
        //Render the page
        $view = new Template();
        echo $view->render('views/signup.html');
    }

    /**
     * Defines a route for the search page. Incorporates search logic, sorting logic, and f3 hive variables for
     * templating.
     *
     * @return void
     * @throws Exception
     */
    function search()
    {
        //If the user searched the page using the searchbar...
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Define default value for filters
            $filters = [];

            //If the user sent a query...
            if (!empty($_POST['query'])) {
                //Add the query to the filters array under the listing name
                $filters['name'] = $_POST['query'];
            }

            //Get the results of the DB query
            $results = $this->_data->getListings($filters);

            //If the user set a sorting method...
            if (!empty($_POST['sort'])) {

                //Sort by the selected method, either price ascending, price descending, or time added.
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
        //Otherwise... (the user did not use the search bar)
        } else {
            //Query the DB for all listings
            $results = $this->_data->getListings();
        }

        //Get the filters and sorts from the data layer
        $this->_f3->set('filterList', $this->_data->getFilters());
        $this->_f3->set('sortList', $this->_data->getSorts());
        //TODO: Set rows responsively
        //Set number of listing cards per row
        $this->_f3->set('row', 3);
        //Set a hive variable to the listings from the DB
        $this->_f3->set('results', $results);
        //Render the page
        $view = new Template();
        echo $view->render('views/search.html');
    }

    /**
     * Defines a route for the listing page. Accepts a code to dynamically render the page via templating.
     *
     * @param string $code A listing code used to dynamically render the page.
     * @return void
     * @throws Exception
     */
    function listing($code)
    {
        //Set the code filter, then query the DB based on the code.
        //Set a hive variable for the result.
        $filters = ['code' => $code];
        $listing = $this->_data->getListings($filters);
        $this->_f3->set('listing', $listing[$code]);

        //Render the page
        $view = new Template();
        echo $view->render('views/listing.html');

    }

    /**
     * Defines a route for HTTP requests to add items to the cart.
     *
     * @return void
     * @throws Exception
     */
    function cartAdd()
    {
        //Fetch cart items from session
        $cart = $this->_f3->get('SESSION.user')->getCart();
        //Fetch code of added item, use it to query DB
        $code = $_POST['code'];
        $listing = $this->_data->getListings(['code'=>$code]);
        //Add it to the cart, add the cart to the user in the SESSION
        $cart[$code] = $listing[$code];
        $this->_f3->get('SESSION.user')->setCart($cart);
        //Render the page
        $view = new Template();
        echo $view->render('views/includes/cart.html');
    }

    /**
     * Define a route for HTTP requests to remove an item from the cart.
     *
     * @return void
     * @throws Exception
     */
    function cartRemove()
    {
        //Fetch cart items from session
        $cart = $this->_f3->get('SESSION.user')->getCart();
        //Fetch the listing code, unset it in the cart
        $code = $_POST['code'];
        unset($cart[$code]);
        //Add the cart back to the user in the SESSION
        $this->_f3->get('SESSION.user')->setCart($cart);
        //Render the page
        $view = new Template();
        echo $view->render('views/cart.html');
    }

    /**
     * Define a route for HTTP requests to clear the cart.
     *
     * @return void
     */
    function cartEmpty()
    {
        //Empty the cart (note that the cart doesn't need to
        // be re-rendered since it is emptied with jQuery)
        $this->_f3->get('SESSION.user')->setCart(null);
    }

    /**
     * Defines a route for the checkout page, validates such that only Customers can place orders.
     *
     * @return void
     * @throws Exception
     */
    function checkout()
    {
        //If the user has attempted to place an order...
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Get the user from the SESSION, set a hive variable to alter
            // the page using templates, set errors to off
            $user = $this->_f3->get('SESSION.user');
            $this->_f3->set('posted',true);
            $error = false;

            //Guard clauses for uninstantiated user, admin instead of customer, and empty cart
            if (empty($user)) {
                $this->_f3->set('userError','Error processing order: User not logged in.');
                $error = true;
            }
            if (!($user instanceof Admin)) {
                $this->_f3->set('adminError','Error processing order: Admins cannot place orders.');
            }
            if (empty($user->getCart())) {
                $this->_f3->set('cartError','Error processing order: Empty cart.');
                $error = true;
            }

            //If there are no errors...
            if (!$error) {

                //Place an order, empty the cart
                $user->placeOrder($this->_data);
                $this->_f3->get('SESSION.user')->setCart(null);
            }
        //Otherwise... (user has not placed an order yet)
        } else {
            //If the user is not logged in...
            if (empty($this->_f3->get('SESSION.user'))) {
                //Reroute them to the login page
                $this->_f3->reroute('login');
            //Otherwise, if the cart is not empty...
            } else if (!empty($this->_f3->get('SESSION.user')->getCart())) {
                //TODO: Display totals on orders page
                //Get the cart and calculate the total cost of the items, then set to a hive var
                $cart = $this->_f3->get('SESSION.user')->getCart();
                $total = 0;
                foreach ($cart as $listing) {
                    $total += $listing->getPrice() * $listing->getSale();
                }
                $this->_f3->set('total', $total);
            }
        }
        //Render the page
        $view = new Template();
        echo $view->render('views/checkout.html');
    }

    /**
     * Define a route for an HTTP request to remove a listing from the website (DB included).
     *
     * @param string $code The code of the listing being removed
     * @return void
     */
    function listingRemove($code)
    {
        //Call the remove listing function on the user (only admins have this capability)
        $this->_f3->get('SESSION.user')->removeListing($code, $this->_data);
    }

    /**
     * Define a route for getting a neatly formatted set of bootstrap cards of listing data.
     *
     * @return void
     * @throws Exception
     */
    function getListings()
    {
        //Query the DB for all listings, save to hive variable
        $results = $this->_data->getListings();
        $this->_f3->set('results', $results);
        //Set number of listing cards per row to 3
        //TODO: Render responsively
        $this->_f3->set('row', 3);
        //Render the page
        $view = new Template();
        echo $view->render('views/includes/get-listings.html');
    }

    /**
     * Define a route for the orders page
     *
     * @return void
     * @throws Exception
     */
    function orders()
    {
        //Get the user and query the DB for their orders, then instantiate their $_orders field.
        $user = $this->_f3->get('SESSION.user');
        $orders = $this->_data->getOrders($user->getEmail());
        $user->setOrders($orders);
        //Render the page
        $view = new Template();
        echo $view->render('views/orders.html');
    }


    //TODO: Currently not in use. These functions are intended for admin control HTTP requests to update database info.

    /**
     * @return void
     */
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

    /**
     * @return void
     * @throws Exception
     */
    function listingUpdate()
    {
        $view = new Template;
        echo $view->render('views/includes/get-listings.html');
    }
}