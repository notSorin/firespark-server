<?php
    //This script is the entry point for network requests related to liking and unliking comments.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/LikeUnlikeComment.php');

        $luc = new LikeUnlikeComment();
        $headers = getallheaders();

        if($luc->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;
                $commentid = $_POST[KEY_COMMENT_ID];
                $action = $_POST[KEY_ACTION];

                if($action == KEY_ACTION_LIKE)
                {
                    if($luc->likeComment($commentid, $userid) === true)
                    {
                        $response = createSuccessResponse(SUCCESS_LIKE_COMMENT);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_LIKE_COMMENT);
                    }
                }
                elseif($action == KEY_ACTION_UNLIKE)
                {
                    if($luc->unlikeComment($commentid, $userid) === true)
                    {
                        $response = createSuccessResponse(SUCCESS_UNLIKE_COMMENT);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_UNLIKE_COMMENT);
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