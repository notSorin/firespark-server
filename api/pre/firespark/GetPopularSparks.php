<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    class GetPopularSparks
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        function getPopularSparks()
        {
            return $this->sparksDAO->getPopularSparks();
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>