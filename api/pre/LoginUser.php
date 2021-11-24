<?php
    require_once(__DIR__ . '/../../../data/Constants.php');
    require_once('firespark/constants.php');
    require_once('firespark/Utils.php');
    require_once('../vendor/autoload.php');
    use \Firebase\JWT\JWT;

    $response = array();

    if(isPostRequest($_SERVER))
    {
        require_once('firespark/LoginUser.php');

        $loginUser = new LoginUser();

        if($loginUser->containsRequiredKeys($_POST))
        {
            $emailOrUsername = $_POST[KEY_EMAIL_OR_USERNAME];
            $password = $_POST[KEY_PASSWORD];
            $userData = $loginUser->loginUser($emailOrUsername, $password);

            if($userData !== null)
            {
                $iat = time();
                $nbf = $iat;
                $exp = $iat + THIRTY_DAYS;

                $tokenPayload = array(
                    "iss" => TOKEN_ISSUER,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "exp" => $exp,
                    "aud" => TOKEN_AUDIENCE,
                    KEY_TOKEN_USER_DATA => $userData
                );

                $token = JWT::encode($tokenPayload, TOKEN_KEY, HS512);

                $response = createSuccessResponse(array(KEY_TOKEN => $token, KEY_USER_ID => $userData[KEY_USER_ID]));
            }
            else
            {
                $response = createBadResponse(ERR_LOGIN_FAILURE);
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