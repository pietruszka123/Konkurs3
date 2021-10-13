<?php
include_once "header.php";
?>

<label for="toggle-1" class="labelaToJeKlasa">I'm a toggle</label>
<input class="inputek" type="checkbox" id="toggle-1">

<div class="messageForm">
    <form method="post">
        <input type="hidden" name="viewMessage" value="messageId" />
        <div class="messagesButtons">
            <?php
            getUserMessages();
            ?>
        </div>
    </form>
</div>
<div class="messageContent">
    <?php
    if (isset($_POST['viewMessage'])) {
        viewMessage($_POST['messageId']);
    }
    ?>
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