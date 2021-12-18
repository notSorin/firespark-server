<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    //This class holds the logic behind getting the followers of a user.
    class GetUserFollowers
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        //Returns an array with the users who follow a certain user, or an empty
        //array if no users follow them, or null on error.
        function getUserFollowers($userId)
        {
            $followers = $this->usersDAO->getUserFollowers($userId);

            if($followers !== null)
            {
                //Remove sensitive information from the users.
                foreach($followers as &$user)
                {
                    unset($user->password);
                    unset($user->email);
                }
            }

            return $followers;
        }

        //Returns true if a keys array and headers array contain all the required keys for getting
        //the followers of a user.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>