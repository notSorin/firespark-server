<?php
    require_once('constants.php');
    require_once(__DIR__ . '/../../vendor/autoload.php');
    use \Firebase\JWT\JWT;

    function isPostRequest($server)
    {
        $ret = false;

        if(array_key_exists('REQUEST_METHOD', $server))
        {
            $ret = $server['REQUEST_METHOD'] == 'POST';
        }

        return $ret;
    }

    function createBadResponse($message)
    {
        $ret = array();

        $ret['code'] = 400;
        $ret['message'] = $message;

        return $ret;
    }

    function createSuccessResponse($message)
    {
        $ret = array();

        $ret['code'] = 200;
        $ret['message'] = $message;

        return $ret;
    }

    function echoAsJSON($response)
    {
        header('Content-Type: application/json');

        echo json_encode($response);
    }

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