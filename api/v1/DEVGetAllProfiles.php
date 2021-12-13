<?php
    //This script is the entry point for network requests related to getting all the profiles on the network.
    //This action is intended for developers only and should not be used by client aplications as it could
    //potentially return big amount of data.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetAllProfiles.php');

        $gap = new GetAllProfiles();
        $headers = getallheaders();

        if($gap->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $profiles = $gap->getAllProfiles();
                
                if($profiles !== null)
                {
                    $response = createSuccessResponse($profiles);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_ALL_PROFILES);
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