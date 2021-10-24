<?php
include_once("header.php");
/*if (CheckRanks("nauczyciel"))
{
    header('Location: /teacher.php');
    exit();
}*/
?>

<ul class="container">
    <li class="grades element">
        <div class="subjectGradesTable">
            <?php
            getUserGrades();
            ?>
        </div>
    </li>
    <li class="attendanceTable element">
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
    </li>
    <li class="timeTable element">
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
    </li>



    <li class="freeDaysTable element">
        <h1>Dni Wolne</h1>
        <?php
        closestFreeDays()
        ?>
    </li>
    <li class="closestExams element">
        <h1>Sprawdziany</h1>
        <?php
        closestExams()
        ?>
    </li>
    <li class="closestHomework element">
        <h1>Zadania Domowe</h1>
        <?php
        closestHomework()
        ?>
    </li>



    <li class="studentComments element">
        <?php
        getUserComments($_SESSION["id"]);
        ?>
    </li>

    <li>
        <div class="daysUntilEndOfYear element">
            <?php
            getDaysUntilEndOfYear()
            ?>
        </div>
        <div class="luckyNumbers element">
            <?php
            getLuckyNumber();
            ?>
        </div>
    </li>
    </div>
</ul>
<script src="js/member.js"></script>


<?php
include_once("footer.php");
?>