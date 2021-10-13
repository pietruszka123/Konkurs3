<?php
    require_once("functions.php");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($uri[2]) {
        case 'sendMessage':
            header("HTTP/1.1 200 OK");
            sendMessage($data["Receiver"],$data["title"],$data["Content"]);
            break;
        
        default:
            //echo getUserMessages();
            //exit();
            echo $uri[2];
            //echo "UwU, somethin went wong.";
            exit();
            break;
    }
?>