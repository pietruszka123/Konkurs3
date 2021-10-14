<?php
include_once('header.php');
?>

<div>
    Plan lekcji<br>

    <?php
    getTimetable();
    ?>

    <form method="POST">
        <input type="submit" name="backward" value="<">
        <input type="submit" name="reset" value="Today">
        <input type="submit" name="forward" value=">">
    </form>
</div>

<?php
include_once('footer.php')
?>