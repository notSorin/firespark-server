<?php
    require_once('DatabaseConnector.php');

    //This class holds a connection to the network's database, and all
    //classes that need to access the database should extend this class.
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