<?php
    require_once('SparksDAO.php');
    require_once('CommentsDAO.php');
    require_once('constants.php');

    class GetSparkData
    {
        private $sparksDAO, $commentsDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
            $this->commentsDAO = new CommentsDAO();
        }
        
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

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>