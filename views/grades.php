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
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <select id="wyborKlasy" name="wyborKlasy" onchange="this.form.submit()">
            <?php
            $_SESSION["wybranaKlasa"] = (isset($_POST["wyborKlasy"])) ? $_POST["wyborKlasy"] : 0;

            ?>
            <?php
            global $mysqli;

            $sql = "SELECT classId, classGrade, classLetter, classType FROM `classes` ORDER BY classId DESC";
            $result = $mysqli->query($sql);
            echo '<option value=" ">Wybierz klasę</option>';
            while ($row = $result->fetch_assoc())
            {
                if (isset($_SESSION["wybranaKlasa"]) && $_SESSION["wybranaKlasa"] == $row['classId'])
                {
                    echo '<option selected value="' . $row['classId'] . '">' . $row['classGrade'] . $row['classLetter'] . ' ' . $row['classType'] . '</option>';
                }
                else
                {


                    echo '<option value="' . $row['classId'] . '">' . $row['classGrade'] . $row['classLetter'] . ' ' . $row['classType'] . '</option>';
                }
            }

            ?>
        </select>
        <select id="wyborPrzedmiotu" name="wyborPrzedmiotu" onchange="this.form.submit()">
            <?php
            $_SESSION["wybranyPrzedmiot"] = (isset($_POST["wyborPrzedmiotu"])) ? $_POST["wyborPrzedmiotu"] : 0;
            echo $_SESSION["wybranyPrzedmiot"];


            ?>
            <?php
            global $mysqli;

            $sql = "SELECT * FROM `subjects`";
            $result = $mysqli->query($sql);

            
            echo '<option value=" ">Wybierz przedmiot</option>';


            while ($row = $result->fetch_assoc())
            {
                $obj = json_decode($row['teacherId']);
                echo json_encode($obj) . " ";
                if (isset($obj->id) && in_array($_SESSION["id"], $obj->id))
                {
                    if (isset($_SESSION["wybranyPrzedmiot"]) && $_SESSION["wybranyPrzedmiot"] == $row['subjectId'])
                    {


                        echo '<option selected value="' . $row['subjectId'] . '">' . $row['subjectName'] . '</option>';
                    }
                    else
                    {
                        echo '<option value="' . $row['subjectId'] . '">' . $row['subjectName'] . '</option>';
                    }
                }
                else
                {
                    echo 'Nie!';
                }
            }
            ?>

        </select>
    </form>
    <table>
    <?php
        echo $_SESSION["wybranaKlasa"];
        echo $_SESSION["wybranyPrzedmiot"];
        getClassSubjectGrades($_SESSION["wybranaKlasa"],$_SESSION["wybranyPrzedmiot"]);
    ?>
    </table>
    <input id="saveGrads" type="button" value="Zapisz zmiany">
    <script src="js/grades.js"></script>
    <?php
    include_once("../footer.php");
    ?>