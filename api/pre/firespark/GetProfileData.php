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
            $profile = $this->usersDAO->getProfileById($userId);
            $profileSparks = $this->sparksDAO->getSparksByUserId($userId);
            
            if($profile != null && $profileSparks != null)
            {
                $ret = array(
                    KEY_PROFILE => $profile,
                    KEY_PROFILE_SPARKS => $profileSparks
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