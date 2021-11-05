<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetSparkData.php');

        $gsd = new GetSparkData();
        $headers = getallheaders();

        if($gsd->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $sparkId = $_POST[KEY_SPARK_ID];
                $sparkData = $gsd->getSparkData($sparkId);
                
                if($sparkData !== null)
                {
                    $response = createSuccessResponse($sparkData);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_SPARK_COMMENTS);
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