<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'dziennik');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli === false)
{
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
session_start();

function getUserGrades()
{
    global $mysqli;
    global $error;

    $sql = "SELECT DISTINCT subjects.subjectId, subjects.subjectName FROM subjects, grades, users WHERE subjects.subjectId = grades.subjectId AND grades.studentId = ?";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["id"];

        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($subjectId, $subjectName);
                echo "<h1>Oceny</h1>";
                while ($stmt->fetch())
                {

                    echo "<div class=\"subjectGradesTitle\">";
                    echo "<h3>" . $subjectName . "</h3>";
                    echo "</div>";
                    echo  "<div class=\"subjectGradesGrades\">";

                    $sql2 = "SELECT DISTINCT gradeScale, gradeWeight, userFirstName, userSecondName, userLastName, gradeDescription, gradeDate 
                    from ( SELECT DISTINCT grades.gradeScale, grades.gradeWeight, users.userFirstName, users.userSecondName, users.userLastName, grades.gradeDescription, grades.gradeDate 
                    FROM users, grades WHERE grades.subjectId = ? AND grades.studentId = ? AND users.userId = grades.teacherId ORDER BY grades.gradeDate DESC LIMIT 5 ) 
                    as source order by gradeDate asc ";

                    if ($stmt2 = $mysqli->prepare($sql2))
                    {
                        $stmt2->bind_param("ss",$subjectId, $param_id);

                        if ($stmt2->execute())
                        {
                            $stmt2->store_result();

                            if ($stmt2->num_rows != 0)
                            {
                                $stmt2->bind_result($gradeScale, $gradeWeight, $userFirstName, $userSecondName, $userLastName, $gradeDescription, $gradeDate);

                                while ($stmt2->fetch())
                                {
                                    echo "<div class=\"singleGrade grade". $gradeScale ."\">";
                                    echo "<p>" . $gradeScale  . "</p>";
                                    echo "<span class=\"gradeGreaterInfo\">";
                                    echo "Nauczyciel: " . $userFirstName . "\n" . $userSecondName . "\n" . $userLastName . "<br>";
                                    echo "Data: " . $gradeDate . "<br>";
                                    echo "<p>Opis: " . $gradeDescription . "</p>";
                                    echo "Waga: " . $gradeWeight;
                                    echo "</span>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    echo "</div>";
                    echo "<div class=\"hr\"></div>";
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            echo "UwU, somethin went wong.";
        }
    }
    $stmt->close();
}

function getUserMessages()
{
    global $mysqli;
    global $error;

    $sql = "SELECT messages.messageId, messages.messageTitle, messages.messageDate, users.userFirstName, users.userSecondName, users.userLastName FROM messages, users WHERE users.userId LIKE messages.senderId AND messages.receiverId LIKE ? ORDER BY messages.messageDate DESC";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = "%" . $_SESSION["id"] . "%";
        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($messageId, $messageTitle, $messageDate, $userFirstName, $userSecondName, $userLastName);
                while ($stmt->fetch())
                {
                    echo "<button name=\"messageId\" value=\"$messageId\" type=\"submit\">
                        <p>".(string)$messageTitle."</p> <p>$messageDate</p> <p>$userFirstName $userSecondName $userLastName</p>
                    </button>";
                   
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            echo "UwU, somethin went wong.";
        }
    }
    $stmt->close();
}

function viewMessage(int $messageId) {
    global $mysqli;
    global $error;

    $sql = "SELECT messages.messageId, messages.messageTitle, messages.messageDate, users.userFirstName, users.userSecondName, users.userLastName, messages.messageContent FROM messages, users WHERE messages.messageId LIKE ? AND users.userId LIKE messages.senderId";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $messageId;
        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($messageId, $messageTitle, $messageDate, $userFirstName, $userSecondName, $userLastName, $messageContent);
                while ($stmt->fetch())
                {
                    echo "<div class=\"tempMessageBox\">
                        <p>" . (string)$messageTitle . "</p> <p>$messageDate</p> <p>" . (string)$userFirstName . " " . (string)$userSecondName. " " . (string)$userLastName ."</p> <p>". (string)$messageContent. "</p>
                    </div>";
                   
                }
            }
            else
            {
                $error .= "UwU, somethin went wong.";
            }
        }
        else
        {
            echo "UwU, somethin went wong.";
        }
    }
    $stmt->close();
}
function viewAllReceivers()
{
    global $mysqli;
    global $error;

    $sql = "SELECT users.userId, users.userFirstName, users.userSecondName, users.userLastName FROM users";

    if ($stmt = $mysqli->prepare($sql))
    {
        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($teacherId, $teacherFirstName, $teacherSecondName, $teacherLastName);
                while ($stmt->fetch())
                {
                    echo "<option value=\"$teacherId\">$teacherFirstName $teacherSecondName $teacherLastName</option>";
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            echo "UwU, somethin went wong.";
        }
    }
    $stmt->close();
}

function sendMessage() {
    global $mysqli;
    global $error;

    $sql = "INSERT INTO `messages` (`senderId`, `receiverId`, `messageContent`, `messageTitle`) VALUES (?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("ssss", $senderId, $receiverId, $messageContent, $messageTitle);
        $senderId = $_SESSION["id"];
        $receiverId = "{ \"id\": [" . $_SESSION["selectReceiver"] . "]}"  ;
        $messageContent = $_SESSION["messageInputContent"];
        $messageTitle = $_SESSION["titleInputBox"];
        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                echo "działa";
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            echo "UwU, somethin went wong.";
        }
    }
    $stmt->close();
}

function getTimeTable() {
    global $mysqli;
    global $error;

    $sql = "SELECT DISTINCT subjects.subjectId, subjects.subjectName FROM subjects, grades, users WHERE subjects.subjectId = grades.subjectId AND grades.studentId = ?";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["id"];

        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($subjectId, $subjectName);
                echo "<h1>Oceny</h1>";
                while ($stmt->fetch())
                {

                    echo "<div class=\"subjectGradesTitle\">";
                    echo "<h3>" . $subjectName . "</h3>";
                    echo "</div>";
                    echo  "<div class=\"subjectGradesGrades\">";

                    $sql2 = "SELECT DISTINCT grades.gradeScale, grades.gradeWeight, users.userFirstName, users.userSecondName, users.userLastName, grades.gradeDescription, grades.gradeDate FROM users, grades WHERE grades.subjectId = ? AND grades.studentId = ? AND users.userId = grades.teacherId ORDER BY grades.gradeDate DESC LIMIT 5";

                    if ($stmt2 = $mysqli->prepare($sql2))
                    {
                        $stmt2->bind_param("ss",$subjectId, $param_id);

                        if ($stmt2->execute())
                        {
                            $stmt2->store_result();

                            if ($stmt2->num_rows != 0)
                            {
                                $stmt2->bind_result($gradeScale, $gradeWeight, $userFirstName, $userSecondName, $userLastName, $gradeDescription, $gradeDate);

                                while ($stmt2->fetch())
                                {
                                    echo "<div class=\"singleGrade grade". $gradeScale ."\">";
                                    echo "<p>" . $gradeScale  . "</p>";
                                    echo "<span class=\"gradeGreaterInfo\">";
                                    echo "Nauczyciel: " . $userFirstName . "\n" . $userSecondName . "\n" . $userLastName . "<br>";
                                    echo "Data: " . $gradeDate . "<br>";
                                    echo "<p>Opis: " . $gradeDescription . "</p>";
                                    echo "Waga: " . $gradeWeight;
                                    echo "</span>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    echo "</div>";
                    echo "<div class=\"hr\"></div>";
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            echo "UwU, somethin went wong.";
        }
    }
    $stmt->close();
}