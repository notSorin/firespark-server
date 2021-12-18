<?php
    //This script is the entry point for network requests related to getting the followers of a user.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetUserFollowers.php');

        $guf = new GetUserFollowers();
        $headers = getallheaders();

        if($guf->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userId = $tokenPayload->userdata->userid;
                $followers = $guf->getUserFollowers($userId);
                
                if($followers !== null)
                {
                    $response = createSuccessResponse($followers);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_USER_FOLLOWERS);
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