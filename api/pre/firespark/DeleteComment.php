<?php
    require_once('CommentsDAO.php');
    require_once('constants.php');

    class DeleteComment
    {
        private $commentsDAO;
        
        function __construct()
        {
            $this->commentsDAO = new CommentsDAO();
        }
        
        function deleteComment($userId, $commentId)
        {
            //Call deleteCommentByIdAndUserId() to make sure the user who wants to delete the comment is its owner.
            return $this->commentsDAO->deleteCommentByIdAndUserId($commentId, $userId);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_COMMENT_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>