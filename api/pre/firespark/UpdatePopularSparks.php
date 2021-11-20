<?php
    require_once('SparksDAO.php');
    require_once('constants.php');

    class UpdatePopularSparks
    {
        private $sparksDAO;
        
        function __construct()
        {
            $this->sparksDAO = new SparksDAO();
        }
        
        function updatePopularSparks()
        {
            return $this->sparksDAO->updatePopularSparks();
        }
    }
?>