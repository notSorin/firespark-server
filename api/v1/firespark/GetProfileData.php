<?php
    require_once('UsersDAO.php');
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind grabbing the data of a user's profile.
    class GetProfileData
    {
        private $usersDAO;
        private $sparksDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
            $this->sparksDAO = new SparksDAO();
        }
        
        //Returns the data of a user's profile (including the user's information and their sparks),
        //or null if the user does not exist or if an error occurs.
        function getProfileData($userId)
        {
            $ret = null;
            $user = $this->usersDAO->getUserById($userId);
            $userSparks = $this->sparksDAO->getSparksByUserId($userId);
            
            if($user !== null && $userSparks !== null)
            {
                //Remove sensitive information from the user.
                unset($user->password);
                unset($user->email);

                $ret = array(
                    KEY_PROFILE => $user,
                    KEY_PROFILE_SPARKS => $userSparks
                );
            }

            return $ret;
        }

        //Returns true if a keys array and headers array contain all the required keys for getting
        //a user's profile data from the network.
        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_USER_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>