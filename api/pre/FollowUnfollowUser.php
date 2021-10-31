<?php
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/FollowUnfollowUser.php');

        $fuUser = new FollowUnfollowUser();
        $headers = getallheaders();

        if($fuUser->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;
                $followeeid = $_POST[KEY_FOLLOWEE_ID];
                $action = $_POST[KEY_ACTION];

                if($action == KEY_ACTION_FOLLOW)
                {
                    if($fuUser->followUser($followeeid, $userid) == true)
                    {
                        $response = createSuccessResponse(SUCCESS_FOLLOW_USER);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_FOLLOW_USER);
                    }
                }
                elseif($action == KEY_ACTION_UNFOLLOW)
                {
                    if($fuUser->unfollowUser($followeeid, $userid) == true)
                    {
                        $response = createSuccessResponse(SUCCESS_UNFOLLOW_USER);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_UNFOLLOW_USER);
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