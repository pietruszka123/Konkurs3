<?php
include_once("header.php");
echo (CheckRanks("nauczyciel")) ? 1 : 2;
if(!CheckRanks("nauczyciel")){
    header('Location: /member.php');
    exit();
}
?>
<?php
include_once("footer.php");
?>