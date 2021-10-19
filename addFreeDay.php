<?php
include_once("header.php");
include_once("views/t_header.php");
?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    <input type="date" id="freeDayDate" name="freeDayDate">
    <input type="text" id="freeDayReason" name="freeDayReason">
    <input type="text" id="freeDayDescription" name="freeDayDescription">
    <input type="submit" class="addFreeDayButton" value="Dodaj">

</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $freeDayDate = trim($_POST['freeDayDate']);
    $freeDayReason = trim($_POST['freeDayReason']);
    $freeDayDescription = trim($_POST['freeDayDescription']);
    
    addFreeDay(
        $freeDayDate,
        $freeDayReason,
        $freeDayDescription
    );
}
?>



<?php



include_once("footer.php");
?>