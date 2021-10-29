<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    class LikeUnlikeSpark
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        function likeSpark($sparkId, $userId)
        {
            return $this->sparksDAO->likeSpark($sparkId, $userId);
        }

        function unlikeSpark($sparkId, $userId)
        {
            return $this->sparksDAO->unlikeSpark($sparkId, $userId);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_ACTION, $keysArray)
                && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>