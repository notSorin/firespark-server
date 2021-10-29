<?php
    require_once('RegisterUser.php');
    require_once('constants.php');

    print __DIR__;
die;
    $ru = new RegisterUser();

    $name = "SOOREEN";

    $name = trim(preg_replace("/\s+/", " ", $name));
    echo "testing [$name] \n";
    if($ru->isFirstLastNameUsable($name))
        echo 'can be used';
    else
        echo 'cannot be used';
?>