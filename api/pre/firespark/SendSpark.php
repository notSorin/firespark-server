<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    class SendSpark
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        function sendSpark($userid, $sparkBody)
        {
            return $this->sparksDAO->insertSpark($userid, $sparkBody);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_BODY, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }

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