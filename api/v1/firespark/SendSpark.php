<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind sending sparks on the network.
    class SendSpark
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        //Adds a new spark on the network, and returns it on success, or null otherwise.
        function sendSpark($userid, $sparkBody)
        {
            return $this->sparksDAO->insertSpark($userid, $sparkBody);
        }

        //Returns true if a keys array and headers array contain all the required keys for sending sparks
        //on the network.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_BODY, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }

        //Performs certain modifications and checks on a spark's body, like trimming white spaces,
        //replacing multiple consecutive spaces with only 1 space, check that its length is correct,
        //and it returns the new body on success, or null if it is not a valid spark body.
        function preProcessSparkBody($sparkBody)
        {
            $newBody = null;
            $sparkBody = trim($sparkBody);

            if(!empty($sparkBody))
            {
                $sparkBody = preg_replace("/\s+/", " ", $sparkBody);
                $sparkLength = strlen($sparkBody);

                if($sparkLength > 0 && $sparkLength <= MAX_SPARK_BODY_LENGTH)
                {
                    $newBody = $sparkBody;
                }
            }

            return $newBody;
        }
    }
?>