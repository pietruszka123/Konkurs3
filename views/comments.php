<?php
include_once("t_header.php");
?>

Wpisz uwagę
<?php
setComment();
?>
<form method="POST">
    <select name="commentType">
        <option value="Uwaga Negatywna">Uwaga Negatywna</option>
        <option value="Uwaga Pozytywna">Uwaga Pozytywna</option>
    </select>
    <select name="commentWeight">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
    </select>
    <input placeholder="Opis" type="text" name="commentContent">
    <select name="studentId">
        <?php
        getTeachersStudents();
        ?>
    </select>
    <input type="submit" name="commentSubmit" value="Utwórz">
</form>

<?php
include_once("../footer.php");
?>