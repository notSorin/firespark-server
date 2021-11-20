<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetPopularSparks.php');

        $gps = new GetPopularSparks();
        $headers = getallheaders();

        if($gps->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $popularSparks = $gps->getPopularSparks();
                
                if($popularSparks !== null)
                {
                    $response = createSuccessResponse($popularSparks);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_POPULAR_SPARKS);
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