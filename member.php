<?php
include_once("header.php");
/*if (CheckRanks("nauczyciel"))
{
    header('Location: /teacher.php');
    exit();
}*/
?>

<!-- marcin zostaw | nie -->
<div class="grades element">
    <div class="subjectGradesTable">
        <?php
        getUserGrades();
        ?>
    </div>
</div>
<div class="daysUntilEndOfYear element">
    <?php
    getDaysUntilEndOfYear()
    ?>
</div>
<div class="timeTable element">
    <?php
    getTimetable();
    ?>

    <form method="POST">
        <input type="submit" name="backward" value="<">
        <input type="submit" name="reset" value="Today">
        <input type="submit" name="forward" value=">">
    </form>
</div>

<div class="freeDaysTable element">
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
<div class="closestHomework">
    <?php
closestHomework()
?>
</div>

<div class="attendanceTable">

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


<script>
    /**skomentyj aby wyłączyć */
    $.ajax({
        type: "post",
        url: "/api.php/getEndTime",
        contentType: "application/json; charset=utf-8",
        success: function(response) {
            var endDate = new Date(response.message * 1000);
            setInterval(() => {
                const today = new Date();
                const days = parseInt((endDate - today) / (1000 * 60 * 60 * 24));
                const hours = parseInt(Math.abs(endDate - today) / (1000 * 60 * 60) % 24);
                const minutes = parseInt(Math.abs(endDate.getTime() - today.getTime()) / (1000 * 60) % 60);
                const seconds = parseInt(Math.abs(endDate.getTime() - today.getTime()) / (1000) % 60);

                $(".daysUntilEndOfYear").html(`<h3> Do końca roku szkolnego pozostało:</h3><p>${days}
                 dni</p><p>
                ${hours + 24 * days}
                 godziny</p><p>
                ${minutes + hours * 60 + 60 * 24 * days}
                 minut</p><p>
                ${seconds + 60 * minutes + 60 * 60 * hours + 60 * 60 * 24 * days}
                 sekund</p>`)
                //`<>DNI DO KONCA ROKU: ${days}<br>GODZINY DO KONCA ROKU: ${hours + 24 * days}<br>;MINUTY DO KONCA ROKU: ${minutes + hours * 60 + 60 * 24 * days}<br>;SEKUNDY DO KONCA ROKU: ${seconds + 60 * minutes + 60 * 60 * hours + 60 * 60 * 24 * days}<br>`)
            }, 1000)
        }
    });
</script>


<?php
include_once("footer.php");
?>