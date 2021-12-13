<?php
    require_once(__DIR__ . '/../../../../data/Constants.php');
    require_once('constants.php');
    require_once(__DIR__ . '/../../vendor/autoload.php');
    use \Firebase\JWT\JWT;

    //Returns true if $server is an array containing a request method, and the
    //request method is POST.
    function isPostRequest($server)
    {
        $ret = false;

        if(array_key_exists('REQUEST_METHOD', $server))
        {
            $ret = $server['REQUEST_METHOD'] == 'POST';
        }

        return $ret;
    }

    //Returns an array with code 400 and $message.
    function createBadResponse($message)
    {
        $ret = array();

        $ret['code'] = 400;
        $ret['message'] = $message;

        return $ret;
    }

    //Returns an array with code 200 and $message.
    function createSuccessResponse($message)
    {
        $ret = array();

        $ret['code'] = 200;
        $ret['message'] = $message;

        return $ret;
    }

    //Echoes $response with a json header.
    function echoAsJSON($response)
    {
        header('Content-Type: application/json');

        echo json_encode($response);
    }

    //Decodes $token and returns its payload on success, or null otherwise.
    function decodeToken($token)
    {
        $payload = null;

        try
        {
            $payload = JWT::decode($token, TOKEN_KEY, array(HS512));
        }
        catch(Exception $ex)
        {
            $payload = null;
        }

        return $payload;
    }
?>