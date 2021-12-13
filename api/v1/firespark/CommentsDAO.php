<?php
    require_once('DatabaseOperation.php');
    require_once('constants.php');
    require_once('Comment.php');

    //Data Access Object which contains functions for manipulating Comments on the database.
    class CommentsDAO extends DatabaseOperation
    {
        function __construct()
        {
            parent::__construct();
        }

        //Returns an array with all the comments for a Spark, or an empty array if the Spark
        //does not have any Comments, or null on error.
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
                        //Add the likes to the comment as they are held separately.
                        $comment->likes = $this->getCommentLikes($comment->commentid);
                        $comments[] = $comment;
                    }
                }
            }

            return $comments;
        }

        //Returns an array with the ids of all the users who have liked a certain comment,
        //or an empty array if the comment does not have any likes, or null on error.
        function getCommentLikes($commentId)
        {
            $likes = null;
            
            if($this->databaseConnection !== null)
            {
                $sql = "select userid
                        from commentslikes
                        where commentid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("i", $commentId);
                
                if($statement->execute())
                {
                    $likes = [];
                    $result = $statement->get_result();

                    while($row = mysqli_fetch_assoc($result))
                    {
                        $likes[] = $row["userid"];
                    }
                }
            }

            return $likes;
        }

        //Returns a comment by its id, or null if the comment does not exist or an error occurred.
        function getCommentById($commentId, $includeDeleted = false, $includeReplyData = false)
        {
            $comment = null;

            if($this->databaseConnection !== null)
            {
                $sql = null;

                if($includeDeleted)
                {
                    $sql = "select commentid, sparkid, userid, body, created, deleted, replytoid, username, firstlastname
                            from comments natural join users
                            where commentid = ?;";
                }
                else
                {
                    $sql = "select commentid, sparkid, userid, body, created, deleted, replytoid, username, firstlastname
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

                        //Also grab the data of the "reply to" comment if this comment is a reply to another.
                        if($includeReplyData && $comment->replytoid !== null)
                        {
                            $replyComment = $this->getCommentById($comment->replytoid, false, false);

                            if($replyComment !== null)
                            {
                                $comment->replytousername = $replyComment->username;
                                $comment->replytofirstlastname = $replyComment->firstlastname;
                            }
                        }
                    }
                }
            }

            return $comment;
        }

        //Updates the "deleted" field to TRUE of a comment by its id. Returns true on success, false otherwise.
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

        //Updates the "deleted" field to TRUE of a comment by its id and owner id. Returns true on success, false otherwise.
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

        //Like a comment given its id and the id of the user who likes the comment.
        //Returns true on success, false otherwise.
        function likeComment($commentId, $userId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                //Consider success if the comment is already liked by the user.
                $success = $this->isCommentLikedByUser($commentId, $userId);

                if(!$success)
                {
                    $sql = "insert into commentslikes (commentid, userid)
                            values (?, ?);";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $commentId, $userId);

                    if($statement->execute())
                    {
                        $success = $statement->affected_rows == 1;
                    }
                }
            }

            return $success;
        }

        //Unlike a comment given its id and the id of the user who unlikes the comment.
        //Returns true on success, false otherwise.
        function unlikeComment($commentId, $userId)
        {
            $success = false;

            if($this->databaseConnection !== null)
            {
                //Consider success if the comment is already unliked by the user.
                $success = !$this->isCommentLikedByUser($commentId, $userId);

                if(!$success)
                {
                    $sql = "delete from commentslikes
                            where commentid = ? and userid = ?;";
                    $statement = $this->databaseConnection->prepare($sql);

                    $statement->bind_param("ii", $commentId, $userId);

                    if($statement->execute())
                    {
                        $success = $statement->affected_rows == 1;
                    }
                }
            }

            return $success;
        }

        //Returns true if a comment is liked by a user, false otherwise.
        function isCommentLikedByUser($commentId, $userId)
        {
            $isLiked = false;

            if($this->databaseConnection !== null)
            {
                $sql = "select *
                        from commentslikes
                        where commentid = ? and userid = ?;";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("ii", $commentId, $userId);
                
                if($statement->execute())
                {
                    $result = $statement->get_result();
                    $isLiked = $result->num_rows == 1;
                }
            }

            return $isLiked;
        }

        //Inserts a comment into the database given a user id, a spark id, a comment body and the id of another
        //comment to reply to (or null if not replying to another comment).
        //Returns the inserted comment on success, null otherwise.
        function insertComment($userId, $sparkId, $commentBody, $replyToId = null)
        {
            $comment = null;

            if($this->databaseConnection !== null)
            {
                $sql = "insert into comments (sparkid, userid, body, replytoid)
                        values (?, ?, ?, ?);";
                $statement = $this->databaseConnection->prepare($sql);

                $statement->bind_param("iisi", $sparkId, $userId, $commentBody, $replyToId);

                if($statement->execute())
                {
                    $commentId = $this->databaseConnection->insert_id;
                    
                    $comment = $this->getCommentById($commentId, false, true);
                }
            }

            return $comment;
        }
    }
?>