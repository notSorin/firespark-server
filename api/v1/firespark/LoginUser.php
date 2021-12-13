<?php
    require_once('UsersDAO.php');
    require_once('constants.php');

    //This class holds the logic behind logging users into the network.
    class LoginUser
    {
        private $usersDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
        }
        
        //Given an email or username and password, the function checks that they are correct and
        //returns an array with the user's id on success, null otherwise.
        function loginUser($emailOrUsername, $password)
        {
            $user = null;

            if(filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) //It's an email.
            {
                $user = $this->usersDAO->getUserByEmailAndPassword($emailOrUsername, $password, false, false);
            }
            else //Should be an username.
            {
                $user = $this->usersDAO->getUserByUsernameAndPassword($emailOrUsername, $password, false, false);
            }

            $ret = null;

            if($user !== null)
            {
                $ret = array(
                    KEY_USER_ID => $user->userid
                );
            }

            return $ret;
        }

        //Returns true if a keys array and headers array contain all the required keys for logging
        //a user into the network.
        function containsRequiredKeys($keysArray)
        {
            return array_key_exists(KEY_EMAIL_OR_USERNAME, $keysArray) && array_key_exists(KEY_PASSWORD, $keysArray);
        }
    }
?>