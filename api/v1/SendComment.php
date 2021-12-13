<?php
    //This script is the entry point for network requests related to sending comments into the network.

    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/SendComment.php');

        $sendComment = new SendComment();
        $headers = getallheaders();

        if($sendComment->containsRequiredKeysAndHeaders($_POST, $headers))
        {
            $token = $headers[KEY_TOKEN_AUTH];
            $tokenPayload = decodeToken($token);

            if($tokenPayload !== null)
            {
                $userid = $tokenPayload->userdata->userid;
                $sparkid = $_POST[KEY_SPARK_ID];
                $commentBody = $sendComment->preProcessCommentBody($_POST[KEY_COMMENT_BODY]);
                $replyToId = $_POST[KEY_COMMENT_REPLYTOID];

                //TODO: Before continuing here, check if the user is allowed to send a comment, because 
                //they might have reached their limit of comments per hour.                
                if($commentBody !== null)
                {
                    $comment = $sendComment->sendComment($userid, $sparkid, $commentBody, $replyToId);

                    if($comment !== null)
                    {
                        $response = createSuccessResponse($comment);
                    }
                    else
                    {
                        $response = createBadResponse(ERR_SEND_COMMENT);
                    }
                }
                else
                {
                    $response = createBadResponse(ERR_SEND_COMMENT);
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