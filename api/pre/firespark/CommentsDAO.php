<?php
    require_once('DatabaseOperation.php');
    require_once('constants.php');
    require_once('Comment.php');

    class CommentsDAO extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }

        function getSparkComments($sparkId, $includeDeleted = false)
        {
            $comments = null;
           
            if($this->databaseConnection !== null)
            {
                if($includeDeleted)
                {
                    $sql = "select c1.commentid, c1.sparkid, c1.userid, c1.body, c1.created, c1.deleted, c1.replytoid, c1.username, c1.firstlastname, c2.username as replytousername, c2.firstlastname as replytofirstlastname
                            from
                            (
                                select commentid, sparkid, comments.userid, body, created, deleted, replytoid, username, firstlastname
                                from comments natural join users
                                where comments.sparkid = ?
                            ) c1
                            left outer join
                            (
                                select commentid, sparkid, comments.userid, body, created, deleted, replytoid, username, firstlastname
                                from comments natural join users
                                where comments.sparkid = ?
                            ) c2 on c1.replytoid = c2.commentid
                            order by c1.created desc;";
                }
                else
                {
                    $sql = "select c1.commentid, c1.sparkid, c1.userid, c1.body, c1.created, c1.deleted, c1.replytoid, c1.username, c1.firstlastname, c2.username as replytousername, c2.firstlastname as replytofirstlastname
                            from
                            (
                                select commentid, sparkid, comments.userid, body, created, deleted, replytoid, username, firstlastname
                                from comments natural join users
                                where comments.sparkid = ? and deleted = FALSE
                            ) c1
                            left outer join
                            (
                                select commentid, sparkid, comments.userid, body, created, deleted, replytoid, username, firstlastname
                                from comments natural join users
                                where comments.sparkid = ? and deleted = FALSE
                            ) c2 on c1.replytoid = c2.commentid
                            order by c1.created desc;";
                }

                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("ii", $sparkId, $sparkId);
                
                if($statement->execute())
                {
                    $comments = [];
                    $result = $statement->get_result();

                    while($comment = $result->fetch_object("Comment"))
                    {
                        $comment->likes = $this->getCommentLikes($comment->commentid);
                        $comments[] = $comment;
                    }
                }
            }

            return $comments;
        }

        //Returns an array with the ids of all the users who have liked a certain comment.
        function getCommentLikes($commentId)
        {
            $likes = [];
            
            if($this->databaseConnection !== null)
            {
                $sql = "select userid
                        from commentslikes
                        where commentid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $commentId);
                
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

        function getCommentById($commentId, $includeDeleted = false)
        {
            $comment = null;
            $sql = null;

            if($includeDeleted)
            {
                $sql = "select commentid, userid, body, created, deleted, replytoid, username, firstlastname
                        from comments natural join users
                        where commentid = ?;";
            }
            else
            {
                $sql = "select commentid, userid, body, created, deleted, replytoid, username, firstlastname
                        from comments natural join users
                        where commentid = ? and deleted = FALSE;";
            }

            $statement = $this->databaseConnection->prepare($sql);

            $statement->bind_param("i", $commentId);
            
            if($statement->execute())
            {
                $result = $statement->get_result();

                if($result->num_rows == 1)
                {
                    $comment = $result->fetch_object("Comment");
                    $comment->likes = $this->getCommentLikes($comment->commentid);
                }
            }

            return $comment;
        }

        function deleteCommentById($commentId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                $sql = "update comments
                        set deleted = TRUE
                        where commentid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $commentId);
                
                if($statement->execute())
                {
                    $success = $statement->affected_rows == 1;
                }
            }

            return $success;
        }

        function deleteCommentByIdAndUserId($commentId, $userId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                $sql = "update comments
                        set deleted = TRUE
                        where commentid = ? and userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("ii", $commentId, $userId);
                
                if($statement->execute())
                {
                    $success = $statement->affected_rows == 1;
                }
            }

            return $success;
        }
    }
?>