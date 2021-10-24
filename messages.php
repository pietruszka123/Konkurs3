<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
if($uri[count($uri)-1] != "headless"){
    include_once "header.php";
}
else {
    include_once "views/t_header.php";
}
?>
<head>
    <link rel="stylesheet" href="/style.css">
    <link href="http://fonts.cdnfonts.com/css/honey-script" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<div class="bg">
    
    <a class="buttonAddMessage2"><img src="/img/1.jpg" alt="" style="height: 30px; width:30px;cursor:pointer;margin-right:5px;"><input type="button" id="Switch" value="Nowa wiadomość" class="buttonAddMessage"></a>
    <a class="buttonRefresh2"><img src="/img/refresh.png" alt="" style="height: 30px; width:30px;cursor:pointer;padding-top:3px;margin-right:5px;"><input type="button" value="Odśwież" id="refresh" class="buttonRefresh"></a>
    <div class="messageForm">
        <input type="hidden" name="viewMessage" value="messageId" />
        <div class="messagesButtons">
            <?php
            getUserMessages();
            ?>
        </div>
    </div>

    <div class="messageContent">
    </div>
    <div class="sendMessageBox" style="display:none;">
        <a class="buttonToWho"><input type="button" id="selectReciver" value="Wybierz odbiroców" class="toWho"></a>
        <hr class="input-line">
        <a class="buttonTitelOfMess"><input type="text" id="titleInputBox" name="titleInputBox" placeholder="Tytuł" style="margin: 0;padding-left:5px;" class="TitelOfMess"></a>
        <hr class="input-line">
        <a class="buttonMessContent"><textarea id="messageInputContent" name="messageInputContent" placeholder="Zawartość"></textarea></a>
        <hr class="input-line">
        <input type="button" class="sendMessageButton" value="Wyślij">
    </div>
    <script src="/js/messages.js" type="module"></script>
    <div id="popUp" class="popUp" style="display: none;">
        <div class="popUp-content">
            <span class="popUp-close">&times;</span>
            <div>
                <div class="notSelected">
                    <?php
                    viewAllReceivers();
                    ?>
                </div>
                <div class="Selected">
                </div>
                <input id="subbmit" type="button" value="zatwierdź wybranych odbiorców" class="zatwierdz">
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>