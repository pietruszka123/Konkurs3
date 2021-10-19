<?php
include_once("header.php");
/*if (CheckRanks("nauczyciel"))
{
    header('Location: /teacher.php');
    exit();
}*/
?>

<!-- marcin zostaw | nie -->
<!-- o co chodzi? -->
<div class="grades element">
    <div class="subjectGradesTable">
        <?php
        getUserGrades();
        ?>
    </div>
</div>
<div class="timeTable element">
    <div class="TimeTableE">
        <h1>Plan Lekcji</h1>
    <?php
    getTimetable();
    ?>
    </div>
    <input type="submit" id="backward" value="<">
    <input type="submit" id="reset" value="Today">
    <input type="submit" id="forward" value=">">
</div>
<div class="daysUntilEndOfYear element">
    <?php
    getDaysUntilEndOfYear()
    ?>
</div>


<div class="freeDaysTable element">
    <h1>Najbliższe Dni Wolne</h1>
    <?php
    closestFreeDays()
    ?>
</div>
<div class="closestExams element">
    <h1>Najbliższe sprawdziany</h1>
    <?php
    closestExams()
    ?>
</div>
<div class="closestHomework element">
    <h1>Zadania Domowe</h1>
    <?php
    closestHomework()
    ?>
</div>

<div class="attendanceTable element">
    <h1>Obecności</h1>
    <?php
    getAttendance($_SESSION["id"]);
    ?>

    <form method="POST">
        <input type="submit" name="back" value="<">
        <input type="submit" name="res" value="Today">
        <input type="submit" name="for" value=">">
    </form>
</div>

<div class="studentComments element">
    <?php
    getUserComments($_SESSION["id"]);
    ?>
</div>


<script src="js/member.js"></script>


<?php
include_once("footer.php");
?>