<?php
    require_once("functions.php");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    switch ($uri[1]) {
        case 'sendMessage':
            echo $_SESSION;
            exit();
            break;
        
        default:
            echo session_encode();//json_encode($_SESSION);
            exit();
            echo "UwU, somethin went wong.";
            exit();
            break;
    }
?>