<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind grabbing home data for users.
    class GetHomeData
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        //Returns an array with all the sparks on a user's home feed, or an empty array
        //if the user does not have any sparks on their home feed, or null on error.
        function getHomeData($userid)
        {
            return $this->sparksDAO->getSparksFromFollowing($userid);
        }

        //Returns true if a keys array and headers array contain all the required keys for following or unfollowind a user.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>