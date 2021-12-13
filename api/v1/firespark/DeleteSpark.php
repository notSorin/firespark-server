<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind deleting a Spark in the network.
    class DeleteSpark
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        //Deletes a Spark belonging to a user, and returns true if the Spark was deleted, false otherwise.
        function deleteSpark($userId, $sparkId)
        {
            //Call deleteSparkByIdAndUserId() to make sure the user who wants to delete the Spark is its owner.
            return $this->sparksDAO->deleteSparkByIdAndUserId($sparkId, $userId);
        }

        //Returns true if a keys array and headers array contain all the required keys for deleting a Spark, false otherwise.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>