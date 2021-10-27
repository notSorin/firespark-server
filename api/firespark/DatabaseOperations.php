<?php
    require_once('DatabaseConnector.php');

    class DatabaseOperations
    {
        private $databaseConnection;

        function __construct()
        {
            $connector = new DatabaseConnector();

            $this->databaseConnection = $connector->getDatabaseConnection();
        }
    }
?>