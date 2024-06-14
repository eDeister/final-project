<?php
/**
 * Describes a class used to fetch data to be used in the controller.
 * Includes a function for SELECTing data from our mySQL database, and functions for getting filters and sorts.
 *
 * @author Ethan Deister <deister.ethan@student.greenriver.edu>
 * @author Eugene Faison
 * @author Abdul Rahmani
 */
class DataLayer
{
    private $_dbh;

    /**
     * Defines the default constructor for the DataLayer. Establishes a connection to the database.
     *
     */
    function __construct()
    {
        // Require my PDO database connection credentials
        require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

        try {
            //Instantiate our PDO Database Object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        //Send an error message if unable to connect
        catch (PDOException $e) {
            die( 'Unable to establish a connection to the database.' );
        }
    }

    /**
     * Queries the database for listing data based on a set of filters.
     *
     * @param array $filters The filters used to query the DB (code, name, brand, sale, type, specification, limit)
     * @return array An array of the resulting Listing objects from the query
     */
    public function getListings($filters = [])
    {
        //Define base SQL query
        $sql = 'SELECT lst.lstCode, lst.lstName, br.brandName, lst.lstPrice, lst.lstSale, lst.lstDesc, specK.specKeyName, specV.specValName 
                FROM listing lst
                JOIN brand br ON lst.brandID = br.brandID 
                JOIN specValLst specVL ON lst.lstID = specVL.lstID
                JOIN specVal specV ON specVL.specValID = specV.specValID
                JOIN specKey specK ON specV.specKeyID = specK.specKeyID';

        $params = [];

        //If any filters have been set...
        if (!empty($filters)) {
            $sql .= ' WHERE 1=1';

            //If the user has searched by code...
            if (!empty($filters['code'])) {
                //Add a clause to SELECT by the code
                $sql .= ' AND lstCode = :code';
                $params[':code'] = $filters['code'];
            }
            //If the user has searched by name...
            if (!empty($filters['name'])) {
                //Add a clause to SELECT by the name
                $sql .= ' AND lstName = :name';
                $params[':name'] = $filters['name'];
            }
            //If the user has filtered by sales
            if (!empty($filters['sale'])) {
                //Add a clause to SELECT listings on sale
                $sql .= ' AND lstSale IS NOT NULL';
            }
            //If the user has filtered by a price range...
            if (!empty($filters['price'])) {
                //Add a clause to SELECT listings in the range
                $sql .= ' AND lstPrice BETWEEN :priceMin AND :priceMax';
                $params[':priceMin'] = $filters['price']['min'];
                $params[':priceMax'] = $filters['price']['max'];
            }
            //If the user has filtered by the instrument type...
            if (!empty($filters['type'])) {
                //Add a clause to SELECT based on the type
                $sql .= ' AND listing.instID = (
                                    SELECT instID 
                                    FROM instrument   
                                    WHERE instType = :type
                                )';
                $params[':type'] = $filters['type'];
            }
            //If the user has filtered by the brand...
            if (!empty($filters['brand'])) {
                //Add a clause to SELECT based on the brand
                $sql .= ' AND listing.brandID = (
                                    SELECT brandID 
                                    FROM brand 
                                    WHERE brandName = :brand
                                )';
                $params[':brand'] = $filters['brand'];
            }
            //If the user has selected any specs to filter by (e.g. speaker wattage)...
            if (!empty($filters['specs'])) {
                //Add a clause to SELECT based on the spec key and value
                $sql .= ' AND lst.lstID IN (
                                        SELECT lstID 
                                        FROM specValLst 
                                        WHERE specValID IN (
                                            SELECT specValID 
                                            FROM specVal 
                                            WHERE specValName IN (:specVals)
                                        )
                                        AND specKeyID IN (
                                                SELECT specKeyID 
                                                FROM specKey
                                                WHERE specKeyName IN (:specKeys)
                                            )    
                                        )';
                $params[':specKeys'] = array_keys($filters['specs']);
                $params[':specVals'] = array_values($filters['specs']);
            }
            //If there is a limit set in the filters...
            if (!empty($filters['limit'])) {
                //Add a clause to limit by a certain number
                $sql .= ' LIMIT :limit';
                $params[':limit'] = $filters['limit'];
            }
        }

        //Prepare, execute, and process the query
        $statement = $this->_dbh->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        //Populate an array of listings using the results
        $listings = [];
        foreach ($result as $row) {
            $lstCode = $row['lstCode'];

            //Add the current listing to the array if it hasn't been added already
            if (!array_key_exists($lstCode, $listings)) {
                $listings[$lstCode] = new Listing(
                    $row['lstCode'],
                    $row['lstName'],
                    $row['brandName'],
                    $row['lstPrice'],
                    $row['lstDesc'],
                    $row['lstSale'],
                    [],
                    $row['instType'],
                    $row['timeAdded']
                );
            }
            //If the listing has been added, that means the only new data is a new spec key/value pairing.
            //Add the key/value pairing to the existing listing
            $listings[$lstCode]->addSpec($row['specKeyName'], $row['specValName']);
        }

        //Return the result of the query
        return $listings;
    }

    /**
     * Removes a listing and its specs from the database.
     *
     * @param string $code The code of the listing being removed
     * @return void
     */
    public function removeListingDB($code)
    {
        //Define the base query
        $sql = '
            DELETE FROM specValLst 
            WHERE lstID = (
                SELECT lstID FROM listing 
                WHERE lstCode = :code
            );
            DELETE FROM listing 
            WHERE lstCode = :code;
            ';

        //Prepare, bind, and execute the query
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
    }

    /**
     * Adds a listing to the database.
     *
     * @param Listing $listing The listing object being added
     * @return void
     */
    public function addListingDB($listing)
    {
        //Define the base query
        $sql = '
            INSERT INTO listing
            VALUES (
                :code, 
                :name, 
                :price, 
                :sale, 
                :desc, 
                (
                    SELECT brandID FROM brand
                    WHERE brandName = :brand
                ), 
                (
                    SELECT instID FROM instrument
                    WHERE instType = :type
                )
            )
        ';

        //Prepare the queryb
        $stmt = $this->_dbh->prepare($sql);

        //Get and bind params
        $code = $listing->getCode();
        $name = $listing->getName();
        $price = $listing->getPrice();
        $sale = $listing->getSale();
        $desc = $listing->getDesc();
        $brand = $listing->getBrand();
        $type = $listing->getType();

        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':sale', $sale);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':type', $type);

        //Execute the query
        $stmt->execute();
    }

    /**
     * Returns an array of filters to be used on the search page.
     *
     * @return array[] A set of filters
     */
    public static function getFilters()
    {
        //TODO: Query the DB for filters
        return [
            'Brand' => ['Roland', 'Fender', 'Yamaha', 'Zildjian', 'Korg'],
            'Type' => ['Piano', 'Guitar', 'Violin', 'Drums', 'Synth']
        ];
    }

    /**
     * Returns a set of sort methods to be used on the search page
     *
     * @return string[] The sort methods
     */
    public static function getSorts()
    {
        return [
            'Name: A-Z',
            'New Arrivals',
            'Price: Ascending',
            'Price: Descending'
        ];
    }

    /**
     * Queries the DB for a user based on their email address.
     *
     * @param string $email The user's email address
     * @return mixed Return the user's information and credentials.
     */
    public function getUserByEmail($email) {
        //Define the base query
        $sql = "
            SELECT * FROM users 
            WHERE email = :email
        ";
        //Prepare, bind, execute, and process+return the result.
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a user in the database.
     *
     * @param string $firstName The user's first name
     * @param string $lastName The user's last name
     * @param string $email The user's email address
     * @param string $password The user's password
     * @return bool Return whether the user was successfully created or not
     */
    public function createUser($firstName, $lastName, $email, $password) {
        //Define the base query
        $sql = "
            INSERT INTO users (first_name, last_name, email, password) 
            VALUES (:first_name, :last_name, :email, :password)
        ";
        //Prepare the statement
        $stmt = $this->_dbh->prepare($sql);

        //Bind the params
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        //Execute the query
        return $stmt->execute();
    }

    /**
     * Places an order based on a customer's email address and the listings in their cart.
     *
     * @param string $email The user's email
     * @param Listing[] $listings The listings in the user's cart
     * @return void
     */
    public function placeOrder($email, $listings) {
        //Define the base query
        $sql = '
            INSERT INTO orders (userID)
            SELECT userID
            FROM users
            WHERE email = :email;
        ';

        //Prepare, bind, and execute the first query
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email',$email);
        $stmt->execute();

        //Get the order ID
        $ordId = $this->_dbh->lastInsertId();

        //For each listing in the user's cart...
        foreach($listings as $listing) {
            //Add a new entry to the intermediary table (Many:Many relationship)
            //describing which listings belong to which orders
            $sql = '
                INSERT INTO ordLst (ordID, lstID)
                VALUES (:ordID, 
                        (SELECT lstID FROM listing
                        WHERE lstCode = :lstCode)
                );
            ';
            //Prepare, bind, and execute the query for the current listing
            $stmt = $this->_dbh->prepare($sql);
            $code = $listing->getCode();
            $stmt->bindParam(':ordID',$ordId, PDO::PARAM_INT);
            $stmt->bindParam(':lstCode', $code);
            $stmt->execute();
        }
    }

    /**
     * Query the database for all Orders placed by a user.
     *
     * @param string $email The user's email, used to query the DB for their orders.
     * @return Order[] Return an array of Order object belonging to the user.
     */
    public function getOrders($email)
    {
        //Define the base query
        $sql = '
            SELECT l.lstCode, l.lstName, b.brandName, l.lstPrice, l.lstSale, o.timeAdded
            FROM listing l
            JOIN brand b ON l.brandID = b.brandID
            JOIN ordLst ol ON l.lstID = ol.lstID
            JOIN orders o ON ol.ordID = o.ordID
            JOIN users u ON o.userID = u.userID
            WHERE  u.email = :email
        ';

        //Prepare,bind,execute the query
        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        //Get all listings
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $orders = [];
        //If the user has placed an order...
        if(!empty($results)) {
            //For each listing in each order...
            foreach ($results as $result) {
                //If the current order has not yet been added...
                if (empty($orders[$result['timeAdded']])) {
                    //Instantiate the order within the $orders array using the current listing data
                    $orders[$result['timeAdded']] = new Order(
                        $listings = array(
                            new Listing(
                                $result['lstCode'],
                                $result['lstName'],
                                $result['lstBrand'],
                                $result['lstPrice'],
                                '',
                                $result['lstSale'],
                                [],
                                '',
                                ''
                            )
                        ),
                        $timestamp = $result['timeAdded']
                    );
                //Otherwise... (the order has already been added)
                } else {
                    //Update the current order's listings array with the current listing
                    $listings = $orders[$result['timeAdded']]->getListings();
                    $listings[] = new Listing(
                        $result['lstCode'],
                        $result['lstName'],
                        $result['lstBrand'],
                        $result['lstPrice'],
                        '',
                        $result['lstSale'],
                        [],
                        '',
                        ''
                    );
                    $orders[$result['timeAdded']]->setListings($listings);
                }
            }
            //Sort by the time that the order was placed
            usort($orders, function($a,$b) {
                return $a->getTimestamp() <=> $b->getTimestamp();
            });
        }
        //Return the array of Orders
        return $orders;
    }

    /**
     * Validates a name
     *
     * @param string $name The name being validated
     * @return bool Return if the name is alphabetical
     */
    public function validName($name)
    {
        //Makes sure the name string is alphabetical
        return preg_match('/^[a-zA-Z]+$/', $name) === 1;
    }

    /**
     * Validates an email
     *
     * @param string $email The email being validated
     * @return mixed Returns whether the email is valid
     */
    public function validEmail($email)
    {
        //Use built-in filter to validate the email
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Validates a password
     *
     * @param string $password The password being validated
     * @return false|int Return whether the password is valid
     */
    public function validPassword($password)
    {
        //Password must be at least 8 chars long, contain one number, one upper case letter, and one special character.
        return preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z]).{8,}$/', $password);
    }
}
