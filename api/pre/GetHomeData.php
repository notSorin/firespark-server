<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetHomeData.php');

        $ghd = new GetHomeData();
        $headers = getallheaders();

        if($ghd->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload != null)
            {
                $userid = $tokenPayload->userdata->userid;
                $homeData = $ghd->getHomeData($userid);
                
                if($homeData != null)
                {
                    $response = createSuccessResponse($homeData);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_HOME_DATA);
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