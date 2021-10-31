<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/GetProfileData.php');

        $gpd = new GetProfileData();
        $headers = getallheaders();

        if($gpd->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload != null)
            {
                $pequestedProfileId = $_POST[KEY_USER_ID];
                $profileData = $gpd->getProfileData($pequestedProfileId);
                
                if($profileData != null)
                {
                    $response = createSuccessResponse($profileData);
                }
                else
                {
                    $response = createBadResponse(ERR_GET_PROFILE_DATA);
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