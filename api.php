<?php
    require_once("functions.php");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    function ERROR()
    {
        echo '{"status":0,"message":"UwU, somethin went wong.}"';
        header("HTTP/1.1 404 ERROR");
        exit();
    }

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $data = json_decode(file_get_contents('php://input'), true);
    if(count($uri) < 3){
        ERROR();
    }
    switch ($uri[2]) {
        case 'sendMessage':
            header("HTTP/1.1 200 OK");
            sendMessage($data["Receiver"],$data["title"],$data["Content"]);
            //getMessageElement("?","now",$data["title"],$data["Content"]);
            break;
        case 'getMessagesElements':
            echo '{"status":true, "message":' .json_encode(array_values(getUserMessages(true))) . '}';
            break;
        case 'getMessageData':
            echo '{"status":true, "message":' .json_encode(array_values(viewMessage($data["messageId"],true))) . '}';
            break;
        case 'getEndTime':
            echo '{"status":true, "message":' . GetTime() . '}';
            break;
        default:
            ERROR();
            break;
    }
