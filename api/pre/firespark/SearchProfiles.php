<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    class SearchProfiles
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        function searchProfiles($name)
        {
            $users = $this->usersDAO->getUsersByName($name, false, false);

            if($users !== null)
            {
                foreach($users as &$user)
                {
                    unset($user->password);
                    unset($user->email);
                }
            }

            return $users;
        }

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_SEARCH_NAME, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>