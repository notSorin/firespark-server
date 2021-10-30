<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    class FollowUnfollowUser
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        function followUser($followeeid, $userid)
        {
            return $this->usersDAO->followUser($followeeid, $userid);
        }

        function unfollowUser($followeeid, $userid)
        {
            return $this->usersDAO->unfollowUser($followeeid, $userid);
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_FOLLOWEE_ID, $keysArray) && array_key_exists(KEY_ACTION, $keysArray)
                && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>