<?php
    require_once('UsersDAO.php');
    require_once('SparksDAO.php');
    require_once('constants.php');

    class GetProfileData
    {
        private $usersDAO;
        private $sparksDAO;
        
        function __construct()
        {
            $this->usersDAO = new UsersDAO();
            $this->sparksDAO = new SparksDAO();
        }
        
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

        function containsRequiredKeysAndHeaders($keysArray, $headers)
        {
            return array_key_exists(KEY_USER_ID, $keysArray) && array_key_exists(KEY_TOKEN_AUTH, $headers);
        }
    }
?>