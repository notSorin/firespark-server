<?php
    //This script is the entry point for network requests related to liking and unliking sparks.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/LikeUnlikeSpark.php');

        $luSpark = new LikeUnlikeSpark();
        $headers = getallheaders();

        if($luSpark->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;
                $sparkid = $_POST[KEY_SPARK_ID];
                $action = $_POST[KEY_ACTION];

                if($action == KEY_ACTION_LIKE)
                {
                    if($luSpark->likeSpark($sparkid, $userid) === true)
                    {
                        $response = createSuccessResponse(SUCCESS_LIKE_SPARK);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_LIKE_SPARK);
                    }
                }
                elseif($action == KEY_ACTION_UNLIKE)
                {
                    if($luSpark->unlikeSpark($sparkid, $userid) === true)
                    {
                        $response = createSuccessResponse(SUCCESS_UNLIKE_SPARK);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_UNLIKE_SPARK);
                    }
                }
                else
                {
                    $response = createBadResponse(ERR_CANNOT_PERFORM_ACTION);
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