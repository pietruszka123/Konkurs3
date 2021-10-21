<?php
include_once("t_header.php");
?>

Wpisz sprawdzian
<?php
setExam();
?>
<form method="POST">
    <input type="date" name="examDate" value="<?php echo date('Y-m-d'); ?>">
    <select name="examSubject">
        <option value="">Wybierz przedmiot</option>
        <?php
        getTeachersSubjects();
        ?>
    </select>
    <input type="text" name="examDescription" placeholder="Opis">
    <input type="text" name="examType" placeholder="Typ egzaminu">
    <select name="examClass">
        <option value="">Wybierz klasę</option>
        <?php
        getTeachersClasses();
        ?>
    </select>
    <input type="submit" name="examSubmit" value="Utwórz">
</form>

<?php
include_once("../footer.php");
?>