<?php
    require_once(__DIR__ . '/../../../../data/Constants.php');

    //This class allows to connect to the network's database.
    class DatabaseConnector
    {
        private $databaseConnection;

        function __construct()
        {
            $newMySqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $this->databaseConnection = $newMySqli->connect_errno ? null : $newMySqli;
        }

        //Returns a MySqli connection to the network's database.
        function getDatabaseConnection()
        {
            return $this->databaseConnection;
        }
    }
?>