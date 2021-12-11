<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    //This class holds the logic behind following and unfollowing a user in the network.
    class FollowUnfollowUser
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        //Follow a user (followeeid) given another user (userid).
        //Returns true on success (or if followeeid was already followed by userid), false otherwise.
        function followUser($followeeid, $userid)
        {
            return $this->usersDAO->followUser($followeeid, $userid);
        }

        //Unfollow a user (followeeid) given another user (userid).
        //Returns true on success (or if followeeid was already unfollowed by userid), false otherwise.
        function unfollowUser($followeeid, $userid)
        {
            return $this->usersDAO->unfollowUser($followeeid, $userid);
        }

        //Returns true if a keys array and headers array contain all the required keys for following or unfollowind a user.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_FOLLOWEE_ID, $keysArray) && array_key_exists(KEY_ACTION, $keysArray)
                && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>