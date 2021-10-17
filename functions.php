<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'dziennik');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysqli === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 100);
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 100);
session_start();
function getClassSubjectGrades($classId, $subjectId)
{
    global $mysqli;
    global $error;
    $sql = "SELECT users.userFirstName,users.userSecondName,users.userLastName,users.userId, grades.gradeScale FROM subjects, grades, users WHERE subjects.subjectId = ? AND grades.classId = ? ORDER BY users.userId";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $subjectId, $classId);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows != 0) {
                $stmt->bind_result($userFirstName, $userSecondName, $userLastName, $userId, $gradeScale);
                $lastid = $userId;
                while ($stmt->fetch()) {
                    if ($lastid != $userId) {
                        echo "</tr>";
                        $lastid = $userId;
                        echo "<tr><td>$userFirstName $userSecondName $userLastName</td>";
                    }
                    echo '<td><input type="text" value="' . $gradeScale . '"  id=""></td>';
                    echo '<td><input id="gradeI" type="text" value="' . $gradeScale . '"  id=""></td>';
                }
                return;
            }
        }
    }
    return "UwU, somethin went wong.";
}
function getUserGrades()
{
    global $mysqli;
    global $error;

    $sql = "SELECT DISTINCT subjects.subjectId, subjects.subjectName FROM subjects, grades, users WHERE subjects.subjectId = grades.subjectId AND grades.studentId = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["id"];

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($subjectId, $subjectName);
                echo "<h1>Oceny</h1>";
                while ($stmt->fetch()) {

                    echo "<div class=\"subjectGradesTitle\">";
                    echo "<h3>" . $subjectName . "</h3>";
                    echo "</div>";
                    echo  "<div class=\"subjectGradesGrades\">";

                    $sql2 = "SELECT DISTINCT gradeScale, gradeWeight, userFirstName, userSecondName, userLastName, gradeDescription, gradeDate 
                    from ( SELECT DISTINCT grades.gradeScale, grades.gradeWeight, users.userFirstName, users.userSecondName, users.userLastName, grades.gradeDescription, grades.gradeDate 
                    FROM users, grades WHERE grades.subjectId = ? AND grades.studentId = ? AND users.userId = grades.teacherId ORDER BY grades.gradeDate DESC  ) 
                    as source order by gradeDate asc ";

                    if ($stmt2 = $mysqli->prepare($sql2)) {
                        $stmt2->bind_param("ss", $subjectId, $param_id);

                        if ($stmt2->execute()) {
                            $stmt2->store_result();

                            if ($stmt2->num_rows != 0) {
                                $stmt2->bind_result($gradeScale, $gradeWeight, $userFirstName, $userSecondName, $userLastName, $gradeDescription, $gradeDate);

                                while ($stmt2->fetch()) {
                                    echo '<div class="singleGrade grade' . $gradeScale . '">';
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
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}

function getUserMessages($ret = false)
{
    if ($ret) $rarr = array();
    global $mysqli;
    global $error;

    $sql = "SELECT messages.messageId, messages.messageTitle, messages.messageDate, users.userFirstName, users.userSecondName, users.userLastName FROM messages, users WHERE users.userId LIKE messages.senderId AND messages.receiverId LIKE ? ORDER BY messages.messageDate DESC";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = "%" . $_SESSION["id"] . "%";
        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($messageId, $messageTitle, $messageDate, $userFirstName, $userSecondName, $userLastName);
                while ($stmt->fetch()) {
                    $messageDate = strip_tags((string)$messageDate);
                    $messageId = strip_tags((string)$messageId);
                    $messageTitle = strip_tags((string)$messageTitle);
                    $userFirstName = strip_tags((string)$userFirstName);
                    $userSecondName = strip_tags((string)$userSecondName);
                    $userLastName = strip_tags((string)$userLastName);
                    $TEMP = "<button name=\"messageId\" id=\"$messageId\" type=\"submit\">
                        <p>$messageTitle </p> <p>\"" . $messageDate . "\"</p> <p>$userFirstName $userSecondName $userLastName&gt</p>
                    </button>";
                    if ($ret) array_push($rarr, $TEMP);
                    else echo $TEMP;
                }
                if ($ret) return $rarr;
            } else {
                if ($ret) return array("0" => "brak wiadomosci do wyświetlenia");
                else echo "brak wiadomosci do wyświetlenia";
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}
function getMessageElement($messageContent, $messageDate, $messageTitle, $userFirstName, $userSecondName, $userLastName)
{
    return "<div class=\"tempMessageBox\">
    <p>$messageTitle</p> <p>$messageDate</p> <p>$userFirstName $userSecondName $userLastName</p> <p>$messageContent</p>
</div>";
}
function viewMessage(int $messageId, $ret = false)
{
    if ($ret) $rarr = array();
    global $mysqli;
    global $error;

    $sql = "SELECT messages.messageId, messages.messageTitle, messages.messageDate, users.userFirstName, users.userSecondName, users.userLastName, messages.messageContent FROM messages, users WHERE messages.messageId LIKE ? AND users.userId LIKE messages.senderId";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $messageId;
        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($messageId, $messageTitle, $messageDate, $userFirstName, $userSecondName, $userLastName, $messageContent);
                while ($stmt->fetch()) {
                    $messageDate = strip_tags((string)$messageDate);
                    $messageContent = strip_tags((string)$messageContent);
                    $messageTitle = strip_tags((string)$messageTitle);
                    $userFirstName = strip_tags((string)$userFirstName);
                    $userSecondName = strip_tags((string)$userSecondName);
                    $userLastName = strip_tags((string)$userLastName);
                    if ($ret) array_push($rarr, getMessageElement($messageContent, $messageDate, $messageTitle, $userFirstName, $userSecondName, $userLastName));
                    else echo getMessageElement($messageContent, $messageDate, $messageTitle, $userFirstName, $userSecondName, $userLastName);
                }
                if ($ret) return $rarr;
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}
function viewAllReceivers()
{
    global $mysqli;
    global $error;

    $sql = "SELECT users.userId, users.userFirstName, users.userSecondName, users.userLastName FROM users";

    if ($stmt = $mysqli->prepare($sql)) {
        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($teacherId, $teacherFirstName, $teacherSecondName, $teacherLastName);
                while ($stmt->fetch()) {
                    echo "<option value=\"$teacherId\">$teacherFirstName $teacherSecondName $teacherLastName</option>";
                }
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}

function sendMessage($Receiver, $title, $Content)
{
    global $mysqli;
    global $error;

    $sql = "INSERT INTO `messages` (`senderId`, `receiverId`, `messageContent`, `messageTitle`) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssss", $senderId, $receiverId, $messageContent, $messageTitle);
        $senderId = $_SESSION["id"];
        $receiverId = "{ \"id\": [\"" . $Receiver . "\"]}";
        $messageContent = $Content;
        $messageTitle = $title;
        if ($stmt->execute()) {
            echo "{\"status\":true}";
            $error = $error . "UwU, somethin went wong.";
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}

/*function getTimetable()
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
}*/
function GetTime()
{
    //POBIERANIE Z BAZY TAK JAK W FUNKCJI NIŻEJ
    $koniecRoku = new DateTime('06/25/2022 12:00 PM');
    return $koniecRoku->getTimestamp();
}
function getDaysUntilEndOfYear()
{

    $now = new DateTime(date('m/d/Y h:i:s a', time())); //AKTUALNY CZAS

    $koniecRoku  = new DateTime('06/25/2022 12:00 PM'); //TU POWINNA BYC DATA WPROWADZONA PRZEZ DYREKTORA ALE NIE MAMY TABELI W BAZIE DANYCH NA TO

    $dni = $koniecRoku->diff($now)->days;
    $godziny = $koniecRoku->diff($now)->h;
    $minuty = $koniecRoku->diff($now)->i;
    $sekundy = $koniecRoku->diff($now)->s;

    echo '<h3> Do końca roku szkolnego pozostało:</h3><p>' . $dni . " dni</p><p>" .
        $godziny + 24 * $dni . " godziny</p><p>" .
        $minuty + $godziny * 60 + 60 * 24 * $dni . " minut</p><p>" .
        $sekundy + 60 * $minuty + 60 * 60 * $godziny + 60 * 60 * 24 * $dni .  " sekund</p>";
}


function getTimetable()
{
    global $mysqli;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['backward'])) {
            $_SESSION['timeTableDate'] = $_SESSION['timeTableDate'] - 1;
        } else if (isset($_POST['forward'])) {
            $_SESSION['timeTableDate'] = $_SESSION['timeTableDate'] + 1;
        } else if (isset($_POST['reset'])) {
            $_SESSION['timeTableDate'] = 0;
        }
    }

    $sql = "SELECT classId FROM `users` WHERE users.userId = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["id"];

        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($classId);
            $stmt->fetch();

            $timeTableDate = $_SESSION['timeTableDate'];

            $sql = "SELECT timetables.subjectId, timetables.teacherId, timetables.classDateStart, timetables.classDateEnd, DATE_FORMAT(timetables.classDateStart, \"%H:%i\") as classStartHour, DATE_FORMAT(timetables.classDateEnd, \"%H:%i\") as classEndHour, timetables.classDescription, timetables.classroom, timetables.obligatory, timetables.substituteTeacherId, timetables.substituteSubjectId, timetables.substituteDescription, timetables.substituteClassroom, timetables.cancelled FROM `timetables` WHERE timetables.classId = $classId AND DATE(timetables.classDateStart) = CURRENT_DATE + INTERVAL $timeTableDate DAY";
            $result = $mysqli->query($sql);

            if ($result->num_rows != 0) {
                while ($row = $result->fetch_assoc()) {
                    if (!isset($row['substitureTeacherId'])) {
                        echo 'Początek lekcji: ' . $row['classStartHour'] . '<br>';
                        echo 'Koniec lekcji: ' . $row['classEndHour'] . '<br>';




                        $teacherId = $row['teacherId'];
                        $sql2 = "SELECT userFirstName, userSecondName, userLastName FROM `users` WHERE userId = $teacherId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();




                        echo 'Nauczyciel: ' . $row2['userFirstName'] . ' ' . $row2['userSecondName'] . ' ' . $row2['userLastName'] . '<br>';
                        echo 'Klasa: ' . $row['classroom'] . '<br>';

                        $subjectId = $row['subjectId'];
                        $sql2 = "SELECT subjectName FROM `subjects` WHERE subjectId = $subjectId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();



                        echo 'Przedmiot: ' . $row2['subjectName'] . '<br>';
                    } else {
                        echo $row['classStartHour'] . '<br>';
                        echo 'Koniec lekcji: ' . $row['classEndHour'] . '<br>';

                        $teacherId = $row['substituteTeacherId'];
                        $sql2 = "SELECT userFirstName, userSecondName, userLastName FROM `users` WHERE userId = $teacherId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();

                        echo 'Nauczyciel: ' . $row2['userFirstName'] . ' ' . $row2['userSecondName'] . ' ' . $row2['userLastName'] . '<br>';
                        echo 'Klasa: ' . $row['classroom'] . '<br>';

                        $subjectId = $row['substituteSubjectId'];
                        $sql2 = "SELECT subjectName FROM `subjects` WHERE subjectId = $subjectId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();

                        echo 'Przedmiot: ' . $row2['subjectName'] . '<br>';
                    }
                }
            } else {
                echo 'Nie ma informacji';
            }
        }
    }

    $currentDate = date("Y/m/d");
    $date = date("Y-m-d", strtotime($currentDate . $_SESSION['timeTableDate'] . ' days'));
    echo $date;

    $stmt->close();
}

function getContactData()
{
    global $mysqli;
    global $error;

    $sql = "SELECT schoolPhoneNumber FROM schoolinformation";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    echo 'Numer szkoły: ' . $row['schoolPhoneNumber'] . '<br>';

    $sql = "SELECT userFirstName, userSecondName, userLastName, userEmail, userPhoneNumber FROM `users` ORDER BY userLastName ASC ";
    $result = $mysqli->query($sql);

    if ($result->num_rows != 0) {
        while ($row = $result->fetch_assoc()) {
            echo 'Nauczyciel: ' . $row['userFirstName'] . ' ' . $row['userSecondName'] . ' ' . $row['userLastName'] . ' ' . 'Email: ' . $row['userEmail'] . 'Numer telefonu: ' . $row['userPhoneNumber'] . '<br>';
        }
    } else {
        $error = $error . "UwU, somethin went wong.";
        echo $error;
    }
}

function getLuckyNumber()
{
    global $mysqli;

    $sql = "SELECT * FROM `luckynumbers` ORDER BY databaseDate DESC";
    $result = $mysqli->query($sql);

    if ($result->num_rows != 0) {
        $row = $result->fetch_assoc();

        if ($row['databaseDate'] != date("Y-m-d")) {
            $luckyNumberFirst = rand(1, 15);
            $luckyNumberSecond = rand(16, 30);

            $sql = "INSERT INTO luckynumbers (databaseDate, luckyNumberFirst, luckyNumberSecond) VALUES (CURRENT_DATE, $luckyNumberFirst, $luckyNumberSecond)";
            $mysqli->query($sql);

            echo $luckyNumberFirst . $luckyNumberSecond;
        } else {
            echo $row['luckyNumberFirst'] . $row['luckyNumberSecond'];
        }
    } else {
        $luckyNumberFirst = rand(1, 15);
        $luckyNumberSecond = rand(16, 30);

        $sql = "INSERT INTO luckynumbers (databaseDate, luckyNumberFirst, luckyNumberSecond) VALUES (CURRENT_DATE, $luckyNumberFirst, $luckyNumberSecond)";
        $mysqli->query($sql);

        echo $luckyNumberFirst . $luckyNumberSecond;
    }
}

function getSchoolInformation()
{
    global $mysqli;

    $sql = "SELECT * FROM `schoolinformation`";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    echo 'Nazwa Szkoły: ' . $row['schoolName'] . '<br>' . 'Adres Szkoły: ' . $row['schoolAddress'] . '<br>' . 'Numer Teleofnu: ' . $row['schoolPhoneNumber'] . '<br>' . 'Dyrektor: ' . $row['schoolPrincipal'];
}
function CheckRanks(...$ranks)
{
    $t = false;
    foreach ($ranks as $value) {
        if (!in_array($value, $_SESSION["rank"])) {
            $t = false;
        } else $t = true;
    }
    return $t;
}
function getUserComments($userID)
{
    global $mysqli;
    global $error;

    $sql = "SELECT comments.commentType, comments.commentWeight, comments.commentContent, comments.commentDate, users.userFirstName, users.userSecondName, users.userLastName FROM comments, users WHERE comments.studentId = ? AND users.userId = comments.teacherId ORDER BY comments.commentDate DESC;";

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $userID;

        if ($stmt->execute()) {
            $stmt->store_result();
            echo "<div class=\"subjectGradesTitle\">";

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($commentType, $commentWeight, $commentContent, $commentDate, $commentTeacherFirstName, $commentTeacherSecondName, $commentTeacherLastName);
                echo "<div class=\"Uwagititle\"><h1>Uwagi</h1></div>";
                while ($stmt->fetch()) {
                    if ($commentType == "Uwaga Negatywna") {
                        echo
                        '<div class="singleComment negatywna">
                           
                            <div class="commentTeacherData">
                            <p>' .
                            $commentTeacherFirstName . " " . $commentTeacherSecondName . " " . $commentTeacherLastName .
                            '</p>
                          </div>
                          <div class="commentWeight">
                            <p> Waga: ' . $commentWeight . '</p>
                         </div>
                          <div class="commentType">
                            <p>' . $commentType . '</p>
                          </div>
                          <div class="commentContent">
                            <p>' . $commentContent . '</p>
                          </div>
                        <div class="commentDate">
                            <p>' . $commentDate . '</p>
                           </div>
                     </div>';
                    } elseif ($commentType == "Uwaga Pozytywna") {
                        echo
                        '<div class="singleComment pozytywna">
                        <div class="commentTeacherData">
                            <p>' .
                            $commentTeacherFirstName . " " . $commentTeacherSecondName . " " . $commentTeacherLastName .
                            '</p>
                        </div>
                        <div class="commentWeight">
                            <p> Waga: ' . $commentWeight . '</p>
                        </div>
                        <div class="commentType">
                            <p>' . $commentType . '</p>
                        </div>
                        <div class="commentContent">
                            <p>' . $commentContent . '</p>
                        </div>
                        <div class="commentDate">
                            <p>' . $commentDate . '</p>
                        </div>
                    </div>';
                    } else { {
                            echo
                            '<div class="singleComment">
                        <div class="commentTeacherData">
                            <p>' .
                                $commentTeacherFirstName . " " . $commentTeacherSecondName . " " . $commentTeacherLastName .
                                '</p>
                        </div>
                        <div class="commentWeight">
                            <p> Waga: ' . $commentWeight . '</p>
                        </div>
                        <div class="commentType">
                            <p>' . $commentType . '</p>
                        </div>
                        <div class="commentContent">
                            <p>' . $commentContent . '</p>
                        </div>
                        <div class="commentDate">
                            <p>' . $commentDate . '</p>
                        </div>
                    </div>';
                        }
                    }
                }
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}


// SELECT subjects.subjectName, users.userFirstName, users.userSecondName, users.userLastName, attendance.subjectNumber, attendance.attendanceState, attendance.attendanceDescription, attendance.attendanceDate, attendance.attendanceExcuse FROM subjects, users, attendance WHERE users.userId = attendance.teacherId AND subjects.subjectId = attendance.subjectId AND attendance.studentId = 3;
function getAttendance($userID)
{
    global $mysqli;
    global $error;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['back'])) {
            $_SESSION['attendanceDate'] = $_SESSION['attendanceDate'] - 1;
        } else if (isset($_POST['for'])) {
            $_SESSION['attendanceDate'] = $_SESSION['attendanceDate'] + 1;
        } else if (isset($_POST['res'])) {
            $_SESSION['attendanceDate'] = 0;
        }
    }
    $attendanceDate = $_SESSION['attendanceDate'];

    $sql = 'SELECT subjects.subjectName, users.userFirstName, users.userSecondName, users.userLastName, attendance.subjectNumber, attendance.attendanceState, attendance.attendanceDescription, attendance.attendanceDate, attendance.attendanceExcuse FROM subjects, users, attendance WHERE users.userId = attendance.teacherId AND subjects.subjectId = attendance.subjectId AND attendance.studentId = ? AND DATE(attendance.attendanceDate) = CURRENT_DATE + INTERVAL ' . strval($attendanceDate) . ' DAY;';
    //$sql = "SELECT timetables.subjectId, timetables.teacherId, timetables.classDateStart, timetables.classDateEnd, DATE_FORMAT(timetables.classDateStart, \"%H:%i\") as classStartHour, DATE_FORMAT(timetables.classDateEnd, \"%H:%i\") as classEndHour, timetables.classDescription, timetables.classroom, timetables.obligatory, timetables.substituteTeacherId, timetables.substituteSubjectId, timetables.substituteDescription, timetables.substituteClassroom, timetables.cancelled FROM `timetables` WHERE timetables.classId = $classId AND DATE(timetables.classDateStart) = CURRENT_DATE + INTERVAL $timeTableDate DAY";



    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $userID;

        if ($stmt->execute()) {
            $stmt->store_result();


            if ($stmt->num_rows != 0) {
                $stmt->bind_result($subjectName, $teacherFirstName, $teacherSecondName, $teacherLastName, $subjectNumber, $attendanceState, $attendanceDescription, $attendanceDateDate, $attendanceExcuse);

                while ($stmt->fetch()) {

                    if ($attendanceState = "Obecnosc") //$attendanceExcuse
                    {
                        echo '<div class="singleAttendance present">
                        <h1>' . $subjectNumber . '</h1>
                        <h3>' . $subjectName . '</h3>
                        <p>' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2>Obecność</h2>
                    </div>';
                    } elseif ($attendanceState = "Spoznienie" && isset($attendanceExcuse)) {

                        echo '<div class="singleAttendance excusedLateness">
                        <h1>' . $subjectNumber . '</h1>
                        <h3>' . $subjectName . '</h3>
                        <p>' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2>Spóźnienie Usprawiedliwione</h2>
                    </div>';
                    } elseif ($attendanceState = "Spoznienie" && !isset($attendanceExcuse)) {
                        echo '<div class="singleAttendance unexcusedLateness">
                        <h1>' . $subjectNumber . '</h1>
                        <h3>' . $subjectName . '</h3>
                        <p>' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2>Spóźnienie Niesuprawiedliwione</h2>
                    </div>';
                    } elseif ($attendanceState = "Nieobecnosc" && isset($attendanceExcuse)) {


                        echo '<div class="singleAttendance excusedAbsence">
                        <h1>' . $subjectNumber . '</h1>
                        <h3>' . $subjectName . '</h3>
                        <p>' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2>Nieobecność Usprawiedliwiona</h2>
                    </div>';
                    } elseif ($attendanceState = "Nieobecnosc" && !isset($attendanceExcuse)) {
                        echo '<div class="singleAttendance unexcusedAbsence">
                        <h1>' . $subjectNumber . '</h1>
                        <h3>' . $subjectName . '</h3>
                        <p>' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2>Nieobecność Niesuprawiedliwiona</h2>
                    </div>';
                    } else {
                        echo '<div class="singleAttendance">
                            <h1>' . $subjectNumber . '</h1>
                            <h3>' . $subjectName . '</h3>
                            <p>' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                            <h2>' . $attendanceState . '</h2>
                        </div>';
                    }
                }
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $currentDate = date("Y/m/d");
    $date = date("Y-m-d", strtotime($currentDate . $_SESSION['attendanceDate'] . ' days'));
    echo $date;

    $stmt->close();
}

function closestFreeDays()
{
    global $mysqli;
    global $error;

    $sql = "SELECT freedays.freeDayDate, freedays.freeDayReason, freedays.freeDayDescription FROM freedays WHERE freeDayDate > CURRENT_DATE ORDER BY freedays.freeDayDate ASC";

    if ($stmt = $mysqli->prepare($sql)) {
        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($freeDayDate, $freeDayReason, $freeDayDescription);
                while ($stmt->fetch()) {
                    echo
                    '<div class="freeDay">
                        <h1>' . $freeDayReason . '</h1>
                        <h2>' . $freeDayDate . '</h2>
                        <p>' . $freeDayDescription . '</p>
                    </div>';
                }
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}


function closestExams()
{
    global $mysqli;
    global $error;

    $sql = "SELECT exams.examDate, subjects.subjectName, users.userFirstName, users.userSecondName, users.userLastName, exams.examDescription, exams.examType FROM exams, subjects, users WHERE exams.classId = ? AND users.userId = exams.teacherId AND subjects.subjectId = exams.subjectId AND exams.examDate > CURRENT_DATE ORDER BY exams.examDate ASC;    ";


    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["classId"];

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($examDate, $subjectName, $FirstName, $SecondName, $LastName, $examDescription, $examType);
         
                while ($stmt->fetch()) {

                    echo '
                    <div class="singleExam">
                        <h1>' . $subjectName . '</h1>
                        <h2>' . $examDescription . '</h2>
                        <h3>' . $examDate . '</h3>
                        <h3>' . $examType . '</h3>
                    </div>
                    ';
                }
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}


function closestHomework()
{
    global $mysqli;
    global $error;

    $sql = "SELECT subjects.subjectName, users.userFirstName, users.userSecondName, users.userLastName, homework.creationDate, homework.deadline, homework.homeworkDescription, homework.obligatory FROM homework, users, subjects WHERE subjects.subjectId = homework.subjectId AND homework.classId = ? AND users.userId = homework.teacherId AND homework.deadline > CURRENT_DATE ORDER BY homework.deadline ASC;";


    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["classId"];

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows != 0) {
                $stmt->bind_result($subjectName, $FirstName, $SecondName, $LastName, $creationDate, $deadline, $homeworkDescription, $obligatory);
              
                while ($stmt->fetch()) {

                    echo '
                    <div class="singleExam">
                        <h1>' . $subjectName . '</h1>
                        <h2>' . $homeworkDescription . '</h2>
                        <h3> Dodano: ' . $creationDate . '</h3>
                        <h3> Do: ' . $deadline . '</h3>
                        <h3> Obowiązkowe? '. $obligatory .'</h3>
                    </div>
                    ';
                }
            } else {
                $error = $error . "UwU, somethin went wong.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}
