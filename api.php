<?php error_reporting(E_ERROR | E_PARSE);
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
    function checkifExists(...$elements){
        //echo array_values($elements);
        
        for ($i=0; $i < count($elements); $i++) { 
            if(isset($elements[$i])){
                ERROR();
            }
        }
    }
    //checkifExists($data["Receiver"]);
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $data = json_decode(file_get_contents('php://input'), true);
    ///if(count($uri) < 3){
/*         ERROR();
 */  //  }
    switch ($uri[count($uri)-1]) {
        case 'sendMessage':
            header("HTTP/1.1 200 OK");
            //checkifExists($data["Receiver"],$data["title"],$data["Content"]);
            sendMessage($data["Receivers"],$data["title"],$data["Content"]);
            //getMessageElement("?","now",$data["title"],$data["Content"]);
            break;
        case 'UpdateGrades':
            header("HTTP/1.1 200 OK");
            UpdateGrades($data);
            break;
        case 'getMessagesElements':
            header("HTTP/1.1 200 OK");
            echo '{"status":true, "message":' .json_encode(array_values(getUserMessages(true))) . '}';
            break;
        case 'getMessageData':
            //checkifExists($data["messageId"]);
            header("HTTP/1.1 200 OK");
            echo '{"status":true, "message":' .json_encode(array_values(viewMessage((int)$data["messageId"],true))) . '}';
            break;
        case 'getEndTime':
            header("HTTP/1.1 200 OK");
            echo '{"status":true, "message":' . GetTime() . '}';
            break;
        case 'getTimeTable':
            header("HTTP/1.1 200 OK");
            echo '{"status":true, "message":"' . getTimetable(true,$data["direction"]) . '"}';
            break;
        case 'getAttendance':
            header("HTTP/1.1 200 OK");
            echo '{"status":true, "message":' . json_encode(getAttendance($_SESSION["id"],true,$data["direction"])) . '}';       
            break;
        default:
            ERROR();
            break;
    }
