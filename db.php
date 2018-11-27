<?php
    function connectToDB($host, $port, $username, $password, $dbName)
    {
        $conn = new PDO("mysql:host=$host;dbname=$dbName;port=$port", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
        return $conn;
    }

    /**
     * * binding parameters defined by question marks and executing the query
     * @param $conn: mysql connection object
     * @param $query: the query
     * @param $params: parameters matching the ? marks from left to right
     * @param $paramsNamesInQuery: an array of the names of the parameters used in the query
     * @param bool $queryIsUpdateOrInsert: define if the query is an update or insert query or not
     * @return array|bool|null
     * @throws Exception if there is a problem in stmt preparation, binding, or execute or when there is a problem with
     * $params or $paramsNamesInQuery
     */
    function executeQuery($conn, $query, $params, $paramsNamesInQuery, $queryIsUpdateOrInsert = true)
    {
        if(count($params) != count($paramsNamesInQuery))
        {
            throw new Exception("The sizes of \$params and \$paramsNamesInQuery are not the same");
        }
        $stmt = $conn->prepare($query);
        for ($i = 0; $i < count($params); $i++)
        {
            if(strlen($paramsNamesInQuery[$i])==0 || $paramsNamesInQuery[$i][0]!=":")
            {
                throw new Exception("Error in \$paramsNamesInQuery[$i]: $paramsNamesInQuery[$i]");
            }
            $stmt->bindParam($paramsNamesInQuery[$i], $params[$i]);
        }
        $stmt->execute();
        if($queryIsUpdateOrInsert)
        {
            $stmt = null;
            return true;
        }
        else
        {
            return $stmt;
        }
    }
    function executeQuery2($conn, $query, $params, $queryIsUpdateOrInsert = true)
    {
        $stmt = $conn->prepare($query);
        $regex = "/(\:[A-Za-z][A-Za-z0-9]*)(?:,|\))/";
        preg_match_all($regex, $query, $matches, PREG_OFFSET_CAPTURE);
        $matches = $matches[0];
        $counter = 0;
        echo "came here";
        echo count($matches);
        foreach ($matches as $array)
        {

            $stmt->bindParam(substr($array[0], 0, strlen($array[0]) - 1), $params[$counter]);
            //echo substr($array[0], 0, strlen($array[0]) - 1) . ":   " . $params[$counter] . "  END\n";
            $counter++;
        }
        $stmt->execute();
        if($queryIsUpdateOrInsert)
        {
            $stmt = null;
            return true;
        }
        else
        {
            return $stmt;
        }
    }

?>