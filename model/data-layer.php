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

    function __construct()
    {
        // Require my PDO database connection credentials
        require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

        try {
            //Instantiate our PDO Database Object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }

    /**
     * @param array $filters
     * @return array
     */
    public function getListings($filters = [])
    {
        //Define SQL query
        $sql = 'SELECT lst.lstCode, lst.lstName, br.brandName, lst.lstPrice, lst.lstSale, lst.lstDesc, specK.specKeyName, specV.specValName 
                FROM listing lst
                JOIN brand br ON lst.brandID = br.brandID 
                JOIN specValLst specVL ON lst.lstID = specVL.lstID
                JOIN specVal specV ON specVL.specValID = specV.specValID
                JOIN specKey specK ON specV.specKeyID = specK.specKeyID';

        $params = [];

        if (!empty($filters)) {
            $sql .= ' WHERE 1=1';

            //If the user has searched by code...
            if (!empty($filters['code'])) {
                $sql .= ' AND lstCode = :code';
                $params[':code'] = $filters['code'];
            }
            //If the user has searched by name...
            if (!empty($filters['name'])) {
                $sql .= ' AND lstName = :name';
                $params[':name'] = $filters['name'];
            }
            //If the user has filtered by sales
            if (!empty($filters['sale'])) {
                $sql .= ' AND lstSale IS NOT NULL';
            }
            //If the user has filtered by a price range
            if (!empty($filters['price'])) {
                $sql .= ' AND lstPrice BETWEEN :priceMin AND :priceMax';
                $params[':priceMin'] = $filters['price']['min'];
                $params[':priceMax'] = $filters['price']['max'];
            }
            //If the user has filtered by the instrument type
            if (!empty($filters['type'])) {
                $sql .= ' AND listing.instID = (
                                    SELECT instID 
                                    FROM instrument   
                                    WHERE instType = :type
                                )';
                $params[':type'] = $filters['type'];
            }
            //If the user has filtered by the brand
            if (!empty($filters['brand'])) {
                $sql .= ' AND listing.brandID = (
                                    SELECT brandID 
                                    FROM brand 
                                    WHERE brandName = :brand
                                )';
                $params[':brand'] = $filters['brand'];
            }
            //If the user has selected any specs to filter by (e.g. speaker wattage)
            if (!empty($filters['specs'])) {
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
                    []
                );
            }
            $listings[$lstCode]->addSpec($row['specKeyName'], $row['specValName']);
        }

        return $listings;
    }

    /**
     * @return array[]
     */
    public static function getFilters()
    {
        return [
            'Brand' => ['Roland', 'Fender', 'Yamaha', 'Zildjian', 'Korg'],
            'Type' => ['Piano', 'Guitar', 'Violin', 'Drums', 'Synth']
        ];
    }

    /**
     * @return string[]
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

    // Get user by email
    public static function getUserByEmail($email) {
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public static function createUser($firstName, $lastName, $email, $password) {
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $stmt = $dbh->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }
}
