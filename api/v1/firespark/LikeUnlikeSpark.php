<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind liking and unliking sparks.
    class LikeUnlikeSpark
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        //Likes a spark given a user id and spark id, and returns true on success,
        //or false otherwise.
        function likeSpark($sparkId, $userId)
        {
            return $this->sparksDAO->likeSpark($sparkId, $userId);
        }

        //Unlikes a spark given a user id and spark id, and returns true on success,
        //or false otherwise.
        function unlikeSpark($sparkId, $userId)
        {
            return $this->sparksDAO->unlikeSpark($sparkId, $userId);
        }

        //Returns true if a keys array and headers array contain all the required keys for liking or
        //unliking a spark.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_ACTION, $keysArray)
                && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>