<?php
include_once "header.php";
?>

<input type="button" id="Switch" value="Toggle">

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

    <!-- <select name="selectReceiver" id="selectReceiver">
        <?php
        //viewAllReceivers();
        ?>
    </select> -->
    <input type="button" id="selectReciver" value="Choose cars to send">
    <input type="text" id="titleInputBox" name="titleInputBox">
    <input type="text" id="messageInputContent" name="messageInputContent">
    <input type="button" class="sendMessageButton" value="wysjil">
</div>
<script src="js/messages.js" type="module"></script>
<div id="popUp" class="popUp">
    <div class="popUp-content">
      <span class="popUp-close">&times;</span>
      <div>
          <select name="selectReceiverType" id="selectReceiverType">
              <option value="b">a</option>
              <option value="b">b</option>
          </select>
      <?php
        //viewAllReceivers();
        ?>
      </div>
    </div>
  </div>


<?php
include_once "footer.php";
?>