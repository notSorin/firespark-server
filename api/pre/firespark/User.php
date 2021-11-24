<?php
    class User
    {
        public $userid, $email, $password, $username, $firstlastname, $joined,
            $verified, $original, $followers, $following;

        function __construct()
        {
            $this->followers = null;
            $this->following = null;
        }
    }
?>