<?php
    //This script is the entry point for network requests related to getting the following of a user.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetUserFollowing.php');

        $guf = new GetUserFollowing();
        $headers = getallheaders();

        if($guf->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userId = $_POST[KEY_USER_ID];
                $following = $guf->getUserFollowing($userId);
                
                if($following !== null)
                {
                    $response = createSuccessResponse($following);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_USER_FOLLOWING);
                }    
            }
            else
            {
                $response = createBadResponse(ERR_INVALID_CREDENTIALS);

                $response[KEY_ERRNO] = ERRNO_INVALID_CREDENTIALS;
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