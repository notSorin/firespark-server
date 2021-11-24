<?php
    require_once(__DIR__ . '/../../../../data/Constants.php');

    class DatabaseConnector
    {
        private $databaseConnection;

        function __construct()
        {
            $newMySqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAMEPRE);

            $this->databaseConnection = $newMySqli->connect_errno ? null : $newMySqli;
        }

        function getDatabaseConnection()
        {
            return $this->databaseConnection;
        }
    }
?>