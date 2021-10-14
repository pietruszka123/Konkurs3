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
    if (isset($_POST['viewMessage']))
    {
        viewMessage($_POST['messageId']);
    }
    ?>
</div>
<div class="sendMessageBox">
    <form method="post" name="formName" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="selectReceiver">Choose a car:</label>

        <select name="selectReceiver" id="selectReceiver">
            <?php
            viewAllReceivers();
            ?>
        </select>
        <input type="text" id="titleInputBox" name="titleInputBox">
        <input type="text" id="messageInputContent" name="messageInputContent">
        <button class="sendMessageButton">wysjil</button>

    </form>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $_SESSION["selectReceiver"] = $_POST["selectReceiver"];
        $_SESSION["messageInputContent"] = $_POST["messageInputContent"];
        $_SESSION["titleInputBox"] = $_POST["titleInputBox"];
        sendMessage();
    }
    ?>
</div>




<?php
include_once "footer.php";
?>