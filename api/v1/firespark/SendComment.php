<?php
    require_once('CommentsDAO.php');
    require_once('constants.php');

    //This class holds the logic behind sending comments on sparks.
    class SendComment
    {
        private $commentsDAO;
        
        function __construct()
        {
            $this->commentsDAO = new CommentsDAO();
        }
        
        //Adds a comment to a spark, and returns the comment on success, or null otherwise.
        function sendComment($userid, $sparkid, $commentBody, $replyToId)
        {
            //The id of the comment that the new comment is replying to must be numeric, otherwise
            //the new comment is not replying to another comment.
            if(!is_numeric($replyToId))
            {
                $replyToId = null;
            }

            return $this->commentsDAO->insertComment($userid, $sparkid, $commentBody, $replyToId);
        }

        //Returns true if a keys array and headers array contain all the required keys for sending comments
        //on sparks.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_COMMENT_BODY, $keysArray) &&
                array_key_exists(KEY_COMMENT_REPLYTOID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }

        //Performs certain modifications and checks on a comment's body, like trimming white spaces,
        //replacing multiple consecutive spaces with only 1 space, check that its length is correct,
        //and it returns the new body on success, or null if it is not a valid comment body.
        function preProcessCommentBody($commentBody)
        {
            $newBody = null;
            $commentBody = trim($commentBody);

            if(!empty($commentBody))
            {
                $commentBody = preg_replace("/\s+/", " ", $commentBody);
                $commentLength = strlen($commentBody);

                if($commentLength > 0 && $commentLength <= MAX_COMMENT_BODY_LENGTH)
                {
                    $newBody = $commentBody;
                }
            }

            return $newBody;
        }
    }
?>