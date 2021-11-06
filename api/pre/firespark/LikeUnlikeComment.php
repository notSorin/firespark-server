<?php
    require_once('CommentsDAO.php');
    require_once('constants.php');

    class LikeUnlikeComment
    {
        private $commentsDAO;
        
        function __construct()
        {
            $this->commentsDAO = new CommentsDAO();
        }
        
        function likeComment($commentId, $userId)
        {
            return $this->commentsDAO->likeComment($commentId, $userId);
        }

        function unlikeComment($commentId, $userId)
        {
            return $this->commentsDAO->unlikeComment($commentId, $userId);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_COMMENT_ID, $keysArray) && array_key_exists(KEY_ACTION, $keysArray)
                && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>