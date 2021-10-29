<?php
    require_once('constants.php');

    class Spark
    {
        public $sparkid, $userid, $body, $created, $deleted, $username,
            $firstlastname, $likes, $comments;

        function __construct()
        {

        }

        function toArray()
        {
            return array(
                KEY_SPARK_ID => $this->id,
                KEY_SPARK_BODY => $this->body,
                KEY_SPARK_CREATED => $this->created,
                KEY_OWNER => $this->owner,
                KEY_LIKES => $this->ownerFirstlastname
            );
        }
    }
?>