Wpisz zadanie domowe
<?php include_once("../functions.php");
setHomework();?>
<form method="POST">
    <input type="datetime-local" name="homeworkDeadline" value="<?php echo date("Y-m-d\TH:i:s"); ?>">
    <select name="homeworkSubject">
        <option value="">Wybierz przedmiot</option>
        <?php
        getTeachersSubjects();
        ?>
    </select>
    <input type="text" name="homeworkDescription" placeholder="Opis zadania">
    <select name="obligatory">
        <option value="">Czy jest obowiązkowe?</option>
        <option value="Nie">Nie</option>
        <option value="Tak">Tak</option>

    </select>
    <select name="homeworkClass">
        <option value="">Wybierz klasę</option>
        <?php
        getTeachersClasses();
        ?>
    </select>
    <input type="submit" name="examSubmit" value="Utwórz">
</form>

