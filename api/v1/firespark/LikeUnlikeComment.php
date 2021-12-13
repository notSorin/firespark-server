<?php
    require_once('CommentsDAO.php');
    require_once('constants.php');

    //This class holds the logic behind liking and unliking comments.
    class LikeUnlikeComment
    {
        private $commentsDAO;
        
        function __construct()
        {
            $this->commentsDAO = new CommentsDAO();
        }
        
        //Likes a comment given a user id and comment id, and returns true on success,
        //or false otherwise.
        function likeComment($commentId, $userId)
        {
            return $this->commentsDAO->likeComment($commentId, $userId);
        }

        //Unlikes a comment given a user id and comment id, and returns true on success,
        //or false otherwise.
        function unlikeComment($commentId, $userId)
        {
            return $this->commentsDAO->unlikeComment($commentId, $userId);
        }

        //Returns true if a keys array and headers array contain all the required keys for liking or
        //unliking a comment.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_COMMENT_ID, $keysArray) && array_key_exists(KEY_ACTION, $keysArray)
                && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>