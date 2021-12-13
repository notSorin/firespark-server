<?php
    require_once('SparksDAO.php');
    require_once('CommentsDAO.php');
    require_once('constants.php');

    //This class holds the logic behind grabbing the data of a spark from the network.
    class GetSparkData
    {
        private $sparksDAO, $commentsDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
            $this->commentsDAO = new CommentsDAO();
        }
        
        //Returns a spark and its comments, or null if the spark does not exist, or if it has
        //been deleted, or if an error occurs.
        function getSparkData($sparkId)
        {
            $ret = null;
            $spark = $this->sparksDAO->getSparkById($sparkId);
            $comments = $this->commentsDAO->getSparkComments($sparkId);

            if($spark !== null && $comments !== null)
            {
                $ret = array(
                    KEY_SPARK => $spark,
                    KEY_SPARK_COMMENTS => $comments
                );
            }

            return $ret;
        }

        //Returns true if a keys array and headers array contain all the required keys for getting
        //a spark's data from the network.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>