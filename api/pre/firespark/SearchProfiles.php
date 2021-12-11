<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    //This class holds the logic behind searching users in the network.
    class SearchProfiles
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        //Returns an array of user profiles matching a name, or an empty array if no profiles
        //are found, or null on error.
        function searchProfiles($name)
        {
            //TODO: Check that name matches a username and a first and last name regex before searching.
            $users = $this->usersDAO->getUsersByName($name, false, false);

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

        //Returns true if a keys array and headers array contain all the required keys for searching
        //user profiles in the network.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SEARCH_NAME, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>