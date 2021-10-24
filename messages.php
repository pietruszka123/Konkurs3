<?php
include_once "header.php";
?>
<div class="bg">
    
    <a class="buttonAddMessage2"><input type="button" id="Switch" value="Nowa wiadomość" class="buttonAddMessage"><img src="img/email.png" alt="" style="height: 2vw; width:2vw; padding-left:1vw;"></a>
    <a class="buttonRefresh2"><input type="button" value="Odśwież" id="refresh" class="buttonRefresh"></a>
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
    <div class="sendMessageBox">
        <a class="buttonToWho"><input type="button" id="selectReciver" value="Wybierz odbirocow" class="toWho"></a>
        <hr class="input-line">
        <a class="buttonTitelOfMess"><input type="text" id="titleInputBox" name="titleInputBox" placeholder="Tytuł" style="margin: 0;" class="TitelOfMess"></a>
        <hr class="input-line">
        <a class="buttonMessContent"><textarea id="messageInputContent" name="messageInputContent" placeholder="Zawartość"></textarea></a>
        <hr class="input-line">
        <input type="button" class="sendMessageButton" value="Wyślij">
    </div>
    <script src="js/messages.js" type="module"></script>
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