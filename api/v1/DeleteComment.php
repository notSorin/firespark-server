<?php
    //This script is the entry point for network requests related to deleting comments.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/DeleteComment.php');

        $dc = new DeleteComment();
        $headers = getallheaders();

        if($dc->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;
                $commentId = $_POST[KEY_COMMENT_ID];

                if($dc->deleteComment($userid, $commentId) === true)
                {
                    $response = createSuccessResponse(SUCCESS_DELETE_COMMENT);
                }
                else
                {
                    $response = createBadResponse(ERR_DELETE_COMMENT);
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