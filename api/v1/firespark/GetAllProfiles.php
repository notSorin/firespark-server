<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    //This class holds the logic behind getting all the profiles in the network.
    class GetAllProfiles
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        //Returns the data of a user's profile (including the user's information and their sparks),
        //or null if the user does not exist or if an error occurs.
        function getAllProfiles()
        {
            $ret = null;
            $users = $this->usersDAO->getAllUsers(true, true);
            
            if($users !== null)
            {
                //Remove sensitive information from the users.
                foreach($users as &$user)
                {
                    unset($user->password);
                    unset($user->email);
                }
            }

            return $users;
        }

        //Returns true if a keys array and headers array contain all the required keys for getting
        //all the users from the network.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>