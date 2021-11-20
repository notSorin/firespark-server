<?php
    //This file should NOT have read permission for OTHERS, because it should
    //only be run locally when necessary.

    require_once('firespark/Utils.php');
    require_once('firespark/UpdatePopularSparks.php');

    $ups = new UpdatePopularSparks();

    if($ups->updatePopularSparks())
    {
        print("The Popular Sparks table has been updated!");
    }
    else
    {
        print("Failed to update the popular Sparks Table.");
    }
?>