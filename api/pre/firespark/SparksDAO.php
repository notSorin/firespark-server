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

            if($this->databaseConnection !== null)
            {
                $sql = "insert into sparks (userid, body)
                        values (?, ?);";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("is", $userId, $sparkBody);

                if($statement->execute())
                {
                    $sparkId = $this->databaseConnection->insert_id;
                    
                    $spark = $this->getSparkById($sparkId);
                }
            }

            return $spark;
        }

        function getSparkById($sparkId, $includeDeleted = false)
        {
            $spark = null;

            if($this->databaseConnection !== null)
            {
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
                            where sparkid = ? and deleted = FALSE;";
                }

                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $sparkId);
                
                if($statement->execute())
                {
                    $result = $statement->get_result();

                    if($result->num_rows == 1)
                    {
                        $spark = $result->fetch_object("Spark");
                        $spark->likes = $this->getSparkLikes($sparkId);
                        $spark->comments = $this->getSparkComments($sparkId);
                    }
                }
            }

            return $spark;
        }

        //Returns an array with the ids of all the users who have commented on a certain spark.
        function getSparkComments($sparkId, $includeDeleted = false)
        {
            $comments = [];
           
            if($this->databaseConnection !== null)
            {
                if($includeDeleted)
                {
                    $sql = "select userid
                            from comments
                            where sparkid = ?;";
                }
                else
                {
                    $sql = "select userid
                            from comments
                            where sparkid = ? and deleted = FALSE;";
                }

                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $sparkId);
                
                if($statement->execute())
                {
                    $result = $statement->get_result();

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $comments[] = $row["userid"];
                    }
                }
            }

            return $comments;
        }

        //Returns an array with the ids of all the users who have liked a certain spark.
        function getSparkLikes($sparkId)
        {
            $likes = [];
           
            if($this->databaseConnection !== null)
            {
                $sql = "select userid
                        from sparkslikes
                        where sparkid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $sparkId);
                
                if($statement->execute())
                {
                    $result = $statement->get_result();

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $likes[] = $row["userid"];
                    }
                }
            }

            return $likes;
        }

        function deleteSparkById($sparkId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                $sql = "update sparks
                        set deleted = TRUE
                        where sparkid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $sparkId);
                
                if($statement->execute())
                {
                    $success = $statement->affected_rows == 1;
                }
            }

            return $success;
        }

        function deleteSparkByIdAndUserId($sparkId, $userId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                $sql = "update sparks
                        set deleted = TRUE
                        where sparkid = ? and userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("ii", $sparkId, $userId);
                
                if($statement->execute())
                {
                    $success = $statement->affected_rows == 1;
                }
            }

            return $success;
        }

        function isSparkLikedByUser($sparkId, $userId)
        {
            $isLiked = false;

            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from sparkslikes
                        where sparkid = ? and userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("ii", $sparkId, $userId);
                
                if($statement->execute())
                {
                    $result = $statement->get_result();
                    $isLiked = $result->num_rows == 1;
                }
            }

            return $isLiked;
        }

        function likeSpark($sparkId, $userId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                //Consider success if the spark is already liked by the user.
                $success = $this->isSparkLikedByUser($sparkId, $userId);

                if(!$success)
                {
                    $sql = "insert into sparkslikes (sparkid, userid)
                            values (?, ?);";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $sparkId, $userId);

                    if($statement->execute())
                    {
                        $success = $statement->affected_rows == 1;
                    }
                }
            }

            return $success;
        }

        function unlikeSpark($sparkId, $userId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                //Consider success if the spark is already unliked by the user.
                $success = !$this->isSparkLikedByUser($sparkId, $userId);

                if(!$success)
                {
                    $sql = "delete from sparkslikes
                            where sparkid = ? and userid = ?;";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $sparkId, $userId);

                    if($statement->execute())
                    {
                        $success = $statement->affected_rows == 1;
                    }
                }
            }

            return $success;
        }

        //Returns an array with sparks from the users whom $userId is following (and their own sparks as well,
        //because it is considered that the user is following themselves), or null on error.
        function getSparksFromFollowing($userId)
        {
            $sparks = null;

            if($this->databaseConnection !== null)
            {
                $sql = "select sparkid, userid, body, created, deleted, username, firstlastname
                        from sparks natural join users
                        where deleted = FALSE and (userid in
                        (
                            select followeeid
                            from followers
                            where userid = ?
                        ) or userid = ?)
                        order by created desc;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("ii", $userId, $userId);
                
                if($statement->execute())
                {
                    $sparks = [];
                    $result = $statement->get_result();

                    while($spark = $result->fetch_object("Spark"))
                    {
                        $spark->likes = $this->getSparkLikes($spark->sparkid);
                        $spark->comments = $this->getSparkComments($spark->sparkid);
                        $sparks[] = $spark;
                    }
                }
            }

            return $sparks;
        }

        //Returns all the sparks belonging to a certain user.
        function getSparksByUserId($userId, $includeDeleted = false)
        {
            $sparks = null;

            if($this->databaseConnection !== null)
            {
                $sql = null;

                if($includeDeleted)
                {
                    $sql = "select sparkid, userid, body, created, deleted, username, firstlastname
                            from sparks natural join users
                            where userid = ?
                            order by created desc;";
                }
                else
                {
                    $sql = "select sparkid, userid, body, created, deleted, username, firstlastname
                            from sparks natural join users
                            where userid = ? and deleted = FALSE
                            order by created desc;";
                }

                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $userId);
                
                if($statement->execute())
                {
                    $sparks = [];
                    $result = $statement->get_result();
                    
                    while($spark = $result->fetch_object("Spark"))
                    {
                        $spark->likes = $this->getSparkLikes($spark->sparkid);
                        $spark->comments = $this->getSparkComments($spark->sparkid);
                        $sparks[] = $spark;
                    }
                }
            }

            return $sparks;
        }

        //Removes all sparks ids from the popularsparks table, and adds new ones.
        function updatePopularSparks()
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                $deleteSql = "delete from popularsparks;";
                $insertSql = "insert into popularsparks (sparkid)
                            select sparks.sparkid
                            from sparks join sparkslikes on sparks.sparkid = sparkslikes.sparkid
                            where deleted = FALSE and sparks.created > NOW() - INTERVAL 14 DAY
                            group by sparks.sparkid
                            order by count(*) desc, sparks.created asc, sparks.sparkid asc
                            limit 10;";

                $deleteStatement = $this->databaseConnection->prepare($deleteSql);
                $insertStatement = $this->databaseConnection->prepare($insertSql);
                
                if($deleteStatement !== false && $insertStatement !== false)
                {
                    $success = $deleteStatement->execute() && $insertStatement->execute();
                }
            }

            return $success;
        }

        function getPopularSparks()
        {
            $sparks = null;

            if($this->databaseConnection !== null)
            {
                $sql = "select s1.sparkid, s1.userid, s1.body, s1.created, s1.deleted, s1.username, s1.firstlastname, count(*) as likesAmount
                        from
                        (
                            select sparkid, userid, body, created, deleted, username, firstlastname
                            from popularsparks natural join sparks natural join users
                            where deleted = FALSE
                            order by created desc
                        ) s1
                        join sparkslikes on s1.sparkid = sparkslikes.sparkid
                        group by s1.sparkid
                        order by likesAmount desc, s1.created asc, s1.sparkid asc;";
                $statement = $this->databaseConnection->prepare($sql);
                
                if($statement->execute())
                {
                    $sparks = [];
                    $result = $statement->get_result();

                    while($spark = $result->fetch_object("Spark"))
                    {
                        $spark->likes = $this->getSparkLikes($spark->sparkid);
                        $spark->comments = $this->getSparkComments($spark->sparkid);
                        $sparks[] = $spark;
                    }
                }
            }

            return $sparks;
        }
    }
?>