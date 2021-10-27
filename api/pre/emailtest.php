<?php
    ini_set('display_errors', '1');
    
    $to      = "dsorin95@gmail.com"; // Send email to our user
    $subject = 'Signup | Verification'; // Give the email a subject 
    $message = '
    
    Thanks for signing up!
    Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
    
    ------------------------
    some other stuff
    ------------------------
    
    Please click this link to activate your account:
    http://www.yourwebsite.com/verify.php?email='
    
    ; // Our message above including the link
                        
    $headers = 'From:noreply@firespark.com' . "\r\n"; // Set from headers
    
    echo mail($to, $subject, $message, $headers); // Send our email
?>