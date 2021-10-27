<?php
    require_once('../firespark/constants.php');
    require_once('../firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('../firespark/RegisterUser.php');

        $registerUser = new RegisterUser();

        if($registerUser->containsRequiredKeys($_POST))
        {
            $email = $_POST[KEY_EMAIL];
            $password = $_POST[KEY_PASSWORD];
            $username = $_POST[KEY_USERNAME];
            $firstlastname = $_POST[KEY_FIRSTLASTNAME];
            $firstlastname = trim(preg_replace("/\s+/", " ", $firstlastname));
            $isEmailUsable = $registerUser->isEmailUsable($email);
            $isPasswordUsable = $registerUser->isPasswordUsable($password);
            $isUsernameUsable = $registerUser->isUsernameUsable($username);
            $isFirstLastNameUsable = $registerUser->isFirstLastNameUsable($firstlastname);

            if($isEmailUsable && $isPasswordUsable && $isUsernameUsable && $isFirstLastNameUsable)
            {
                if($registerUser->createUser($email, $password, $username, $firstlastname))
                {
                    $response = createSuccessResponse(ERR_CREATE_USER);
                }
                else
                {
                    $response = createBadResponse(ERR_CREATE_USER);
                }
            }
            else
            {
                if(!$isEmailUsable)
                    $response = createBadResponse(ERR_CANNOT_USE_EMAIL);
                elseif(!$isPasswordUsable)
                    $response = createBadResponse(ERR_CANNOT_USE_PASSWORD);
                elseif(!$isUsernameUsable)
                    $response = createBadResponse(ERR_CANNOT_USE_USERNAME);
                elseif(!$isFirstLastNameUsable)
                    $response = createBadResponse(ERR_CANNOT_USE_FIRSTLASTNAME);
            }
        }
        else
        {
            $response = createBadResponse(ERR_MISSING_REQUIRED_KEYS);
        }
    }
    else
    {
        $response = createBadResponse(ERR_BAD_REQUEST);
    }

    echoAsJSON($response);
?>