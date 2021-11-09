<?php
    require_once('CommentsDAO.php');
    require_once('constants.php');

    class SendComment
    {
        private $commentsDAO;
        
        function __construct()
        {
            $this->commentsDAO = new CommentsDAO();
        }
        
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

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SPARK_ID, $keysArray) && array_key_exists(KEY_COMMENT_BODY, $keysArray) &&
                array_key_exists(KEY_COMMENT_REPLYTOID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }

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