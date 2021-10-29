<?php
    require_once('DatabaseOperation.php');
    require_once('constants.php');
    require_once('Spark.php');

    class SparksDAO extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }

        function insertSpark($userId, $sparkBody)
        {
            $spark = null;
            $sql = "insert into sparks (userid, body)
                    values (? , ?);";
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

        function getSparkById($sparkId, $includeDeleted = false)
        {
            $spark = null;
            $sql = null;

            if($includeDeleted)
            {
                $sql = "select sparkid, userid, body, created, deleted, username, firstlastname
                        from sparks natural join users
                        where sparkid = ?;";
            }
            else
            {
                $sql = "select sparkid, userid, body, created, deleted, username, firstlastname
                        from sparks natural join users
                        where sparkid = ? and deleted = 0;";
            }

            $statement = $this->databaseConnection->prepare($sql);

            $statement->bind_param("i", $sparkId);
            
            $statement->execute();

            $result = $statement->get_result();

            if($result->num_rows == 1)
            {
                $spark = $result->fetch_object("Spark");
                $spark->likes = $this->getSparkLikes($sparkId);
                $spark->comments = $this->getSparkComments($sparkId);
            }

            return $spark;
        }

        //Returns an array with the ids of all the users who have commented on a certain spark.
        function getSparkComments($sparkId)
        {
            $comments = [];
           
            if($this->databaseConnection != null)
            {
                $sql = "select userid
                        from comments
                        where sparkid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $sparkId);
                
                $statement->execute();

                $result = $statement->get_result();

                while($row = mysqli_fetch_assoc($result))
                {
                    $comments[] = $row["userid"];
                }
            }

            return $comments;
        }

        //Returns an array with the ids of all the users who have liked a certain spark.
        function getSparkLikes($sparkId)
        {
            $likes = [];
           
            if($this->databaseConnection != null)
            {
                $sql = "select userid
                        from sparkslikes
                        where sparkid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $sparkId);
                
                $statement->execute();

                $result = $statement->get_result();

                while($row = mysqli_fetch_assoc($result))
                {
                    $likes[] = $row["userid"];
                }
            }

            return $likes;
        }
    }
?>