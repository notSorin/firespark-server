<?php
    //This script is the entry point for network requests related to searching for user profiles.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/SearchProfiles.php');

        $sp = new SearchProfiles();
        $headers = getallheaders();

        if($sp->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $searchName = $_POST[KEY_SEARCH_NAME];
                $profiles = $sp->searchProfiles($searchName);
                
                if($profiles !== null)
                {
                    $response = createSuccessResponse($profiles);
                }
                else
                {
                    $response = createBadResponse(ERR_SEARCH_NAMES);
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