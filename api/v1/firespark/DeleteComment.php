<?php
    require_once('CommentsDAO.php');
    require_once('constants.php');

    //This class holds the logic behind deleting a Comment in the network.
    class DeleteComment
    {
        private $commentsDAO;
        
        function __construct()
        {
            $this->commentsDAO = new CommentsDAO();
        }
        
        //Deletes a Comment belonging to a user, and returns true if the Comment was deleted, false otherwise.
        function deleteComment($userId, $commentId)
        {
            //Call deleteCommentByIdAndUserId() to make sure the user who wants to delete the Comment is its owner.
            return $this->commentsDAO->deleteCommentByIdAndUserId($commentId, $userId);
        }

        //Returns true if a keys array and headers array contain all the required keys for deleting a Comment, false otherwise.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_COMMENT_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>