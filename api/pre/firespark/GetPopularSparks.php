<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind grabbing popular sparks from the network.
    class GetPopularSparks
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        //Returns an array with all the popular sparks on the network, an empty array if there are no
        //popular sparks on the network, or null otherwise.
        function getPopularSparks()
        {
            return $this->sparksDAO->getPopularSparks();
        }

        //Returns true if a keys array and headers array contain all the required keys for getting popular
        //sparks from the network.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>