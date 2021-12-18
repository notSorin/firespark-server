<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    //This class holds the logic behind getting the following of a user.
    class GetUserFollowing
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        //Returns an array with the users whom a user is following, or an empty
        //array if the user does not follow anyone, or null on error.
        function getUserFollowing($userId)
        {
            $following = $this->usersDAO->getUserFollowing($userId);

            if($following !== null)
            {
                //Remove sensitive information from the users.
                foreach($following as &$user)
                {
                    unset($user->password);
                    unset($user->email);
                }
            }

            return $following;
        }

        //Returns true if a keys array and headers array contain all the required keys for getting
        //the following of a user.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_USER_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>