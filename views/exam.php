Wpisz sprawdzian
<?php
include_once("../functions.php");
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
    <input type="text" name="examDescription" placeholder="Opis testu">
    <input type="text" name="examType" placeholder="Typ testu">
    <select name="examClass">
        <option value="">Wybierz klasę</option>
        <?php
        getTeachersClasses();
        ?>
    </select>
    <input type="submit" name="examSubmit" value="Utwórz">
</form>