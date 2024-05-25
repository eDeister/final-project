<?php
class DataLayer
{
    function getTestListings()
    {
        return array(
            new Listing('instr1','Test name 1', 'Test brand 1', 5, 'Test desc 1', array('spec1' => 'spec1', 'spec2'=>'spec2')),
            new Listing('instr2','Test name 2', 'Test brand 2', 5, 'Test desc 2', array('spec1' => 'spec1', 'spec2'=>'spec2')),
            new Listing('instr3','Test name 3', 'Test brand 3', 5, 'Test desc 3', array('spec1' => 'spec1', 'spec2'=>'spec2')),
            new Listing('instr4','Test name 4', 'Test brand 4', 5, 'Test desc 4', array('spec1' => 'spec1', 'spec2'=>'spec2')),
        );
        //TODO: Use a PDO to query a database for listings based on filters and sort
    }

    function getListings($filters)
    {
        $dbh = $GLOBALS['dbh'];

        //Define SQL query
        $sql = 'SELECT lstName, lstPrice, lstBrand, lstSale FROM listing, instrument, brand, specVal, specKey,'
            .'specValLst';


        if(!empty($filters)){
            $sql .= ' WHERE ';
            //If the user has searched by name...
            if(!empty($filters['name'])){
                //Complete the query (define, prepare, bind, execute) with the name
                $sql .= ' lstName = :name';
                $statement = $dbh->prepare($sql);
                $statement->bindParam(':name', $filters['name']);
                $statement->execute();
            } else {
                $params = [];
                $sql = $sql.' 1=1';

                //If the user has filtered by sales
                if(!empty($filters['sale'])){
                    $sql .= 'AND lstSale IS NOT NULL';
                }

                //If the user has filtered by a price range
                if(!empty($filters['price'])){
                    $sql .= 'AND lstPrice BETWEEN :priceMin AND :priceMax';
                    $params[':priceMin'] = $filters['price']['min'];
                    $params[':priceMax'] = $filters['price']['max'];
                }

                //If the user has filtered by the instrument type
                if(!empty($filters['type'])){
                    //Use a subquery to find all listings of selected instrument type
                    $sql .= 'AND listing.instID = (
                                    SELECT instID 
                                    FROM instrument   
                                    WHERE instType = :type
                                ) ';
                    $params[':type'] = $filters['type'];
                }

                //If the user has filtered by the brand
                if(!empty($filters['brand'])){
                    //Use a subquery to find all listings
                    $sql .= 'AND listing.brandID = (
                                    SELECT brandID 
                                    FROM brand 
                                    WHERE brandName = :brand
                                ) ';
                    $params[':brand'] = $filters['brand'];
                }

                //If the user has selected any specs to filter by (e.g. speaker wattage)
                if(!empty($filters['specs'])){
                    $index = 1;
                    $keyPlaceholders = [];
                    $valPlaceholders = [];
                    foreach($filters['specs'] as $key => $val){
                        $keyPlaceholders[] = ':key'.$index;
                        $valPlaceholders[] = ':val'.$index;
                        $params['key'.$index] = $key;
                        $params['val'.$index] = $val;
                        $index++;
                    }
                    $sql .= 'AND listing.specID = (
                                        SELECT specID 
                                        FROM specValLst 
                                        WHERE specValID = (
                                            SELECT specValID 
                                            FROM specVal 
                                            WHERE specValName IN ('.implode(', ', $valPlaceholders).')
                                            AND specKeyID IN (
                                                SELECT specKeyID 
                                                FROM specKey
                                                WHERE specKeyName IN ('.implode(', ', $keyPlaceholders).')
                                            )
                                        )';

                }
            }

        }
    }

    function getFilters()
    {
        $dbh = $GLOBALS['dbh'];

    }
}