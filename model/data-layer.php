<?php
class DataLayer
{

    static function getListings($filters)
    {
        $dbh = $GLOBALS['dbh'];

        //Define SQL query
        $sql = 'SELECT lst.lstCode, lst.lstName, br.brandName, lst.lstPrice, lst.lstSale, lst.lstDesc, specK.specKeyName,
                    specV.specValName 
                FROM listing lst
                JOIN brand br ON lst.brandID = br.brandID 
                JOIN specValLst specVL ON lst.specID = specVL.specID
                JOIN specVal specV ON specVL.specValID = specV.specValID
                JOIN specKey specK ON specV.specKeyID = specK.specKeyID';

        $params = [];

        if(!empty($filters)) {
            $sql .= ' WHERE 1=1';

            //If the user has searched by code...
            if (!empty($filters['code'])) {

                //Add the name as a clause (and parameter)
                $sql .= ' AND lstCode = :code';
                $params[':code'] = $filters['code'];

            }
            //If the user has searched by name...
            if (!empty($filters['name'])) {

                //Add the name as a clause (and parameter)
                $sql .= ' AND lstName = :name';
                $params[':name'] = $filters['name'];
            }
            //If the user has filtered by sales
            if(!empty($filters['sale'])){

                //Add sale requirement clause
                $sql .= ' AND lstSale IS NOT NULL';
            }
            //If the user has filtered by a price range
            if(!empty($filters['price'])){
                //Add min and max price as a clause (and params)
                $sql .= ' AND lstPrice BETWEEN :priceMin AND :priceMax';
                $params[':priceMin'] = $filters['price']['min'];
                $params[':priceMax'] = $filters['price']['max'];
            }

            //If the user has filtered by the instrument type
            if(!empty($filters['type'])){

                //Use a subquery to find all listings of selected instrument type
                $sql .= ' AND listing.instID = (
                                    SELECT instID 
                                    FROM instrument   
                                    WHERE instType = :type
                                ) ';
                $params[':type'] = $filters['type'];
            }

            //If the user has filtered by the brand
            if(!empty($filters['brand'])){

                //Use a subquery to find all listings
                $sql .= ' AND listing.brandID = (
                                    SELECT brandID 
                                    FROM brand 
                                    WHERE brandName = :brand
                                ) ';
                $params[':brand'] = $filters['brand'];
            }

            //If the user has selected any specs to filter by (e.g. speaker wattage)
            if(!empty($filters['specs'])){

                //
                $sql .= ' AND lst.specID IN (
                                        SELECT specID 
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
        $statement = $dbh->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        //Populate an array of listings using the results
        $listings = [];
        foreach ($result as $row) {
            $lstCode = $row['lstCode'];

            //Add the current listing to the array if it hasn't been added already
            if (!array_key_exists($lstCode, $listings)) {
                $listings[$lstCode] = new Listing($row['lstCode'],$row['lstName'],$row['brandName'],$row['lstPrice'],
                    $row['lstSale'],$row['lstDesc'], array());
                //Otherwise, this means that the listing just needs more specifications added to its specs assoc arr
            }
            $listings[$lstCode]->addSpec($row['specKeyName'],$row['specValName']);
        }


        return $listings;
    }

    static function getFilters()
    {
        $dbh = $GLOBALS['dbh'];

    }
}