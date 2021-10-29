<?php
    require_once('constants.php');

    class User
    {
        public $userid, $email, $password, $username, $firstlastname, $joined,
            $verified, $original, $followers, $following;

        function __construct()
        {

        }

        function toArray()
        {
            return array(
                KEY_USER_ID => $this->userid,
                KEY_USER_EMAIL => $this->email,
                KEY_USER_USERNAME => $this->username,
                KEY_USER_FIRSTLASTNAME => $this->firstlastname,
                KEY_USER_JOINED => $this->joined,
                KEY_USER_VERIFIED => $this->verified,
                KEY_USER_ORIGINAL => $this->original,
                KEY_USER_FOLLOWERS => $this->followers,
                KEY_USER_FOLLOWING => $this->following,
            );
        }
    }
?>