<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/DeleteSpark.php');

        $deleteSpark = new DeleteSpark();
        $headers = getallheaders();

        if($deleteSpark->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;
                $sparkid = $_POST[KEY_SPARK_ID];

                if($deleteSpark->deleteSpark($userid, $sparkid) === true)
                {
                    $response = createSuccessResponse(SUCCESS_DELETE_SPARK);
                }
                else
                {
                    $response = createBadResponse(ERR_DELETE_SPARK);
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