<?php
include_once "header.php";
?>

<label for="toggle-1" class="labelaToJeKlasa">I'm a toggle</label>
<input class="inputek" type="checkbox" id="toggle-1">

<input type="button" value="Odświerz" id="refresh">
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
    <label for="selectReceiver">Choose a car:</label>

    <select name="selectReceiver" id="selectReceiver">
        <?php
        viewAllReceivers();
        ?>
    </select>
    <input type="text" id="titleInputBox" name="titleInputBox">
    <input type="text" id="messageInputContent" name="messageInputContent">
    <input type="button" class="sendMessageButton" value="wysjil">

</div>
<script src="/js/messages.js"></script>



<?php
include_once "footer.php";
?>