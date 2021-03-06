<?php
    //This script is the takes care of updating the popular sparks on the network and logging the output.
    //It should NOT have read permission for OTHERS, because it should only be run locally when necessary.

    require_once('firespark/Utils.php');
    require_once('firespark/UpdatePopularSparks.php');

    $ups = new UpdatePopularSparks();
    $logFile = "/var/www/html/api/v1/update.log";

    error_log(date("Y-m-d H:i:s") . " -> ", 3, $logFile);

    if($ups->updatePopularSparks())
    {
        error_log("The Popular Sparks table has been updated!\n", 3, $logFile);
    }
    else
    {
        error_log("Failed to update the popular Sparks Table.\n", 3, $logFile);
    }
?>