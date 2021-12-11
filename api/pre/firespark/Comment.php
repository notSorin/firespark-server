<?php
    //Class to hold data for a Comment in the network.
    class Comment
    {
        public $commentid, $sparkid, $userid, $body, $created, $deleted,
            $replytoid, $likes, $username, $firstlastname;

        function __construct()
        {

        }
    }
?>