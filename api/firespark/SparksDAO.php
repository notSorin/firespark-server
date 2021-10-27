<?php
    require_once('DatabaseOperation.php');
    require_once('constants.php');

    class SparksDAO extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }

        function sendSpark($userId, $sparkBody)
        {
            $spark = null;
            $sql = "insert into sparks (userid, body) values (? , ?);";
            $statement = $this->databaseConnection->prepare($sql);

            $statement->bind_param("is", $userId, $sparkBody);
            $success = $statement->execute();

            if($success)
            {
                $sparkId = $this->databaseConnection->insert_id;
                
                $spark = $this->getSparkById($sparkId, $userId);
            }

            return $spark;
        }

        private function getSparkById($sparkId, $requesterId)
        {
            $spark = null;
            $sql = "select * from sparks join users on sparks.userid = users.userid where sparkid = ? and deleted = 0;";
            $statement = $this->databaseConnection->prepare($sql);

            $statement->bind_param("i", $sparkId);
            
            $statement->execute();

            $result = $statement->get_result();

            if($result->num_rows == 1)
            {
                $row = $result->fetch_assoc();

                //TODO: Create a Spark class and set its likes and comments amount, and if it is liked and has a comment from the requester user.
                $spark = array(
                    KEY_SPARK_ID => $row[KEY_SPARK_ID],
                    KEY_SPARK_BODY => $row[KEY_SPARK_BODY],
                    KEY_SPARK_CREATED => $row[KEY_SPARK_CREATED],
                    KEY_USERNAME => $row[KEY_USERNAME],
                    KEY_FIRSTLASTNAME => $row[KEY_FIRSTLASTNAME]
                );
            }

            return $spark;
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

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_BODY, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>