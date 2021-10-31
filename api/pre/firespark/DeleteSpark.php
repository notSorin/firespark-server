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
            $success = false;
            $spark = $this->sparksDAO->getSparkById($sparkId);
            
            if($spark !== null)
            {
                //Make sure the user who wants to delete the spark is its owner.
                if($spark->userid == $userId)
                {
                    $success = $this->sparksDAO->deleteSparkById($sparkId);
                }
            }

            return $success;
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>