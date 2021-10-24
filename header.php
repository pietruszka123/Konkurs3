<?php


include_once("functions.php");
if(!isset($_SESSION["id"])){
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="http://fonts.cdnfonts.com/css/honey-script" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Dziennik</title>
</head>

<body>
    <div class="tlo"></div>
    <header><img class="plaster" src="img/ggg.svg" alt="">
        <h1>Hive Grades</h1>
        <a href="login.php" style="position:fixed; top:0.9283819628647215vh; right:1.3020833333333333vw;">
            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sign-out-alt" class="logout" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="#f7d61f" d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path>
            </svg>
        </a>
        <?php
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        if($uri[count($uri)-1] != "login.php" && $uri[count($uri)-1] != "register.php"){
        echo '<nav class="HorizontalNavBar">
            <ol>
                <a href="member.php">
                    <li id="Home">Home</li>
                </a>
                <a href="messages.php">
                    <li id="messages">wiadomosci</li>
                </a>

            </ol>
        </nav>
    </header>';
        }
    ?>
    <link href="http://fonts.cdnfonts.com/css/honey-script" rel="stylesheet">