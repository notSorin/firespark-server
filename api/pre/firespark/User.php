<?php
    //Class to hold data for a User in the network.
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