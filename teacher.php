<?php
include_once("header.php");
echo (CheckRanks("nauczyciel")) ? 1 : 2;
if (!CheckRanks("nauczyciel")) {
    header('Location: /member.php');
    exit();
}
?>
<div class="klasy">
    <div>
        <div class="Przedmiot">Przedmiot</div>
    </div>
    <div>
        a
    </div>
    <div>
        a
    </div>
    <div>
        a
    </div>
    <div>
        a
    </div>
    <div>
        a
    </div>
    <div>
        a
    </div>
    <div>
        a
    </div>
</div>

<table>
    <tr>
        <td>uczniowie</td>
        <td><input type="text" value="1"  id=""></td>
        <td><input type="text" value="2" id=""></td>
        <td><input type="text" value="3" id=""></td>
        <td><input type="text" value="4" id=""></td>
        <td><input type="text" value="5" id=""></td>
        <td><input type="text" value="6" id=""></td>
        <td><input type="text" value="7" id=""></td>
        <td><input type="text" value="8" id=""></td>
    </tr>
    <?php
        getClassSubjectGrades(1,1);
    ?>
</table>
<script src="js/teacher.js"></script>

<?php
include_once("footer.php");
?>