<?php
include_once(dirname(__DIR__) ."/functions.php");
if (!CheckRanks("nauczyciel")) {
    header('Location: /member.php');
    exit();
}
?>