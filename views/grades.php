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
    <?php
        getClassSubjectGrades(1,1);
    ?>
</table>
<input type="button" value="Zapisz zmiany">
<script src="js/grades.js"></script>
<?php
include_once("../footer.php");
?>