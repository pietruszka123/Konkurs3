<?php
include_once('header.php');
?> 
<div class="attendanceTable">
<!-- <div class="singleAttendance">
    <h1>$subjectNumber</h1>
    <h3>$subjectName</h3>
    <p>$teacherFirstName, $teacherSecondName, $teacherLastName,</p>
    <h2>$attendanceState</h2>
</div> -->

   <?php
    getAttendance($_SESSION["id"]);
    ?>

<form method="POST">
        <input type="submit" name="back" value="<">
        <input type="submit" name="res" value="Today">
        <input type="submit" name="for" value=">">
    </form>
</div>


<?php
include_once('footer.php')
?>
