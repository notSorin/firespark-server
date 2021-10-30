<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    class GetHomeData
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        function getHomeData($userid)
        {
            return $this->sparksDAO->getSparksFromFollowing($userid);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>