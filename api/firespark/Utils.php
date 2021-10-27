<?php
    require_once('constants.php');

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
?>