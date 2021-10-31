<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/SendSpark.php');

        $sendSpark = new SendSpark();
        $headers = getallheaders();

        if($sendSpark->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;

                //TODO: Before continuing here, check if the user is allowed to send a spark, because 
                //they might have reached their limit of sparks per hour.

                $sparkBody = $sendSpark->preProcessSparkBody($_POST[KEY_SPARK_BODY]);

                if($sparkBody !== null)
                {
                    $spark = $sendSpark->sendSpark($userid, $sparkBody);

                    if($spark !== null)
                    {
                        $response = createSuccessResponse($spark);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_SEND_SPARK);
                    }
                }
                else
                {
                    $response = createBadResponse(ERR_SEND_SPARK);
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