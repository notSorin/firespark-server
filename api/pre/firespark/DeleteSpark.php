<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    class DeleteSpark
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        function deleteSpark($userId, $sparkId)
        {
            //Call deleteSparkByIdAndUserId() to make sure the user who wants to delete the spark is its owner.
            return $this->sparksDAO->deleteSparkByIdAndUserId($sparkId, $userId);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>