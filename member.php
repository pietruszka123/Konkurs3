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


<gridzio>
<div class="grades element">
    <div class="subjectGradesTable">
        <?php
        getUserGrades();
        ?>
    </div>
</div>
<div class="timeTable element">
    <h1>Plan Lekcji</h1>
    <div class="TimeTableE">

          <?php
        getTimetable();
        ?>
    </div>
    <div class='strzalkiZmienne T'>
        <input type="submit" id="backward" value="<">
        <input type="submit" id="reset" value="Today">
        <input type="submit" id="forward" value=">">
    </div>
</div>
<div>
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
</div>
<div class="luckyNumbers element">
    <?php
        getLuckyNumber();
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
    <div class="attendanceTableE">
    <?php
    getAttendance($_SESSION["id"]);
    ?>
    </div>
    <div class='strzalkiZmienne A'>
    <input type='submit' id='back' value='<'>
    <input type='submit' id='res' value='Today'>
    <input type='submit' id='for' value='>'>
    </div>
</div>

<div class="studentComments element">
    <?php
    getUserComments($_SESSION["id"]);
    ?>
</div>
</gridzio>

<script src="js/member.js"></script>


<?php
include_once("footer.php");
?>