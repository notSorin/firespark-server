<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    //This class holds the logic behind updating the popular sparks on the network.
    class UpdatePopularSparks
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        //Updates the popular sparks on the network. Returns true on success, false otherwise.
        function updatePopularSparks()
        {
            return $this->sparksDAO->updatePopularSparks();
        }
    }
?>