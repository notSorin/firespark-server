<?php
    require_once('../firespark/constants.php');
    require_once('../firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('../firespark/LoginUser.php');

        $loginUser = new LoginUser();

        if($loginUser->containsRequiredKeys($_POST))
        {
            $emailOrUsername = $_POST[KEY_EMAIL_OR_USERNAME];
            $password = $_POST[KEY_PASSWORD];
            $userData = $loginUser->loginUser($emailOrUsername, $password);

            if($userData != null)
            {
                $response = createSuccessResponse($userData);
            }
            else
            {
                $response = createBadResponse(ERR_LOGIN_FAILURE);
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