<?php
include_once("t_header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="http://fonts.cdnfonts.com/css/honey-script" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <ol>
        <li><a id>Klasa 1</a></li>
        <li><a></a></li>
        <li><a></a></li>
        <li><a></a></li>
    </ol>
    <table>
    <tr>
        <td>uczniowie</td>
        <?php
           /*         <td><input type="text" value="1"  id=""></td>
                    <td><input type="text" value="2" id=""></td>
                    <td><input type="text" value="3" id=""></td>
                    <td><input type="text" value="4" id=""></td>
                    <td><input type="text" value="5" id=""></td>
                    <td><input type="text" value="6" id=""></td>
                    <td><input type="text" value="7" id=""></td>
                    <td><input type="text" value="8" id=""></td>*/
            //getGradesTitles()
        ?>
    </tr>
    <?php
        getClassSubjectGrades(1,1);
    ?>
</table>
<input type="button" value="Zapisz zmiany">
<script src="js/grades.js"></script>
<?php
include_once("../footer.php");
?>