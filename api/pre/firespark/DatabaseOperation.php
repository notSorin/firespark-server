<?php
    require_once('DatabaseConnector.php');

    class DatabaseOperation
    {
        protected $databaseConnection;

        function __construct()
        {
            $connector = new DatabaseConnector();

            $this->databaseConnection = $connector->getDatabaseConnection();
        }
    }
?>