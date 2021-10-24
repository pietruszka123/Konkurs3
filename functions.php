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
function hasId($arr,$s)
{
    foreach ($arr as $element) {
        if ($element->userId == $s){
            return true;
        }
    }
    return false;
}
function getaId($arr,$s)
{
    $i = 0;
    foreach ($arr as $element) {
        if ($element->userId == $s){
            return $i;
        }
        $i++;
    }
    return -1;
}
function parseGrade($grade){
    $GradesCodes = array("1.25"=>"+1","2.25"=>"+2","3.25"=>"+3","4.25"=>"+4","5.25"=>"+5","6.25"=>"+6", 0.75=>"-1",1.75=>"-2",2.75=>"-3",3.75=>"-4",4.75=>"-5",5.75=>"-6");
    if(array_key_exists($grade,$GradesCodes)){
        return $GradesCodes[$grade];
    }else
    return $grade;
}
function UpdateGrades($data){
    global $mysqli;
    global $error;
    $GradesCodes = array("+1"=>1.25,"+2"=>2.25,"+3"=>3.25,"+4"=>4.25,"+5"=>5.25,"+6"=>6.25,"-1"=>0.75,"-2"=>1.75,"-3"=>2.75,"-4"=>3.75,"-5"=>4.75,"-6"=>5.75);
    $queries = "";
    if(array_key_exists("labels",$data)){
        foreach ($data["labels"] as $key => $value) {
            if($value["empty"]){
                $queries .= 'INSERT INTO gradecolumns(gradeWeight,gradeDescription,classId,columnPosition, subjectId) VALUES('.$value["weight"].',"' .$value["desc"].'",'.$data["ClassId"] .','. $key .','.$data["SubjectId"].');';
            }else{
                
            }
        }  
    }
    $queries = "";
    foreach ($data["Users"] as $key => $value) {
        foreach ($value["grades"] as $gKey => $gValue) {
            if(is_numeric($gValue["value"]) || array_key_exists($gValue["value"],$GradesCodes)){
                if(array_key_exists($gValue["value"],$GradesCodes)){
                    $gValue["value"] = $GradesCodes[$gValue["value"]];
                }
                if($gValue["empty"]){
                    $queries .= "INSERT INTO grades (studentId, gradeScale, gradeWeight,teacherId,gradeDescription,subjectId,classId,columnId) VALUES (".$value["id"].",".$gValue["value"].",".$gValue["GradesData"]["weight"].",".$_SESSION["id"].",'".$gValue["GradesData"]["desc"]."',".$data["SubjectId"].",".$data["ClassId"].",".$gKey+1 .");";
                }else{
                    $queries .= "UPDATE grades SET studentId = ".$value["id"].", gradeScale = ".$gValue["value"]. ", gradeWeight = ".$gValue["GradesData"]["weight"].", teacherId = ".$_SESSION["id"].", gradeDescription = '".$gValue["GradesData"]["desc"]."', subjectId = ".$data["SubjectId"]." , classId = ".$data["ClassId"].", columnId = ".$gValue["GradesData"]["id"] ." WHERE gradeId = ". $gValue["id"] .";";
                }
            }
        }
    }
    if($mysqli->multi_query($queries)){
        echo "{'status':true}";
    }else{

        echo "{'status':false,'message':$mysqli->error}";
    }
}
function addLabel()
{
    global $mysqli;
    global $error;
    $sql = "";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("ss", $subjectId, $classId);
        if ($stmt->execute()){

        }
    }
}
function getClassSubjectGrades($classId, $subjectId)
{
    global $mysqli;
    global $error;
    $sql = "SELECT users.userFirstName,users.userSecondName,users.userLastName,users.userId, grades.gradeScale,gradecolumns.columnPosition,gradecolumns.gradeDescription,gradecolumns.gradeWeight,grades.gradeId,gradecolumns.columnId FROM subjects, grades, users,gradecolumns WHERE grades.subjectId = ? AND grades.classId = ? AND subjects.subjectId = grades.subjectId AND grades.columnId = gradecolumns.columnId AND grades.studentId = users.userId ORDER BY users.userId;";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("ss", $subjectId, $classId);
        if ($stmt->execute())
        {
            $stmt->store_result();
            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($userFirstName, $userSecondName, $userLastName, $userId, $gradeScale,$columnPosition,$gradeDescription,$gradeWeight,$gradeId,$columnId);
                $t = array();
                $max = 0;
                $maxColl =0; 
                echo "<tr><td>uczniowie</td>";
                $ids = array();
                while ($stmt->fetch())
                {
                    if(hasId($t,$userId)){
                        $id = getaId($t,$userId);
                        array_push($t[$id]->gradeScales,$gradeScale);
                        array_push($t[$id]->gradeIds,$gradeId);
                        if(count($t[$id]->gradeScales) > $max) $max = count($t[$id]->gradeScales);
                    }else{
                        array_push($ids,$userId);
                        if(1 > $max) $max = 1;
                        array_push($t,(object) ['userFirstName' => $userFirstName,'userSecondName' => $userSecondName,'userLastName'=> $userLastName,'userId' => $userId,'gradeIds'=>array(0=> $gradeId),'gradeScales'=> array(0=> $gradeScale)]);
                    }
                    if($columnPosition > $maxColl){
                        echo "<td data-id='$columnId' id='$columnPosition' class='GradeDesc'><input autocomplete='off' type='text' id='Desc' value='$gradeDescription'> <input autocomplete='off' type='text' id='Weight' value='$gradeWeight'></td>";
                        $maxColl = $columnPosition;
                    }
                }
                echo "<td><input id='addLabel' type='button' value='+'></td>";
                $ii = 0;
                echo "max ". $max;
                foreach ($t as $key=>$element) {
                    echo "<tr data-id='".$ids[$key]."' id='t$key'><td class='User'>".$element->userFirstName. $element->userSecondName .$element->userLastName."</td>";
                    for ($i=0; $i < $max; $i++) {
                        if(count($element->gradeScales) > $i)echo "<td class='Grade'><input data-id='".$element->gradeIds[$i]."' autocomplete='off' class='ocenaI' id='i".$ii."' type='text' value='" . parseGrade($element->gradeScales[$i]) . "'></td>";
                        else echo "<td class='Grade'><input autocomplete='off' data-empty='true' class='ocenaI' id='i".$ii."' type='text'></td>";
                        $ii++;
                    }
                    echo "</tr>";
                }
                return;
            }
        }
    }
    echo "UwU, somethin went wong.";
}

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
                    FROM users, grades WHERE grades.subjectId = ? AND grades.studentId = ? AND users.userId = grades.teacherId ORDER BY grades.gradeDate DESC  ) 
                    as source order by gradeDate asc ";

                    if ($stmt2 = $mysqli->prepare($sql2))
                    {
                        $stmt2->bind_param("ss", $subjectId, $param_id);

                        if ($stmt2->execute())
                        {
                            $stmt2->store_result();

                            if ($stmt2->num_rows != 0)
                            {
                                $stmt2->bind_result($gradeScale, $gradeWeight, $userFirstName, $userSecondName, $userLastName, $gradeDescription, $gradeDate);

                                while ($stmt2->fetch())
                                {
                                    $gradeScale = parseGrade($gradeScale);
                                    echo '<div class="singleGrade grade' . str_replace(array("-","+"), "", $gradeScale) . '">';
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
                    $messageDate = strip_tags((string)$messageDate);
                    $messageId = strip_tags((string)$messageId);
                    $messageTitle = strip_tags((string)$messageTitle);
                    $userFirstName = strip_tags((string)$userFirstName);
                    $userSecondName = strip_tags((string)$userSecondName);
                    $userLastName = strip_tags((string)$userLastName);
                    $TEMP = "<button name=\"messageId\" id=\"$messageId\" type=\"submit\">
                        <p class='titel'>$messageTitle </p><p class='juzer'>$userFirstName $userSecondName $userLastName</p> <p class='dacior'> $messageDate </p> 
                    </button>";
                    if ($ret) array_push($rarr, $TEMP);
                    else echo $TEMP;
                }
                if ($ret){ 
                    $stmt->close();
                    return $rarr;
                }
            }
            else
            {
                if ($ret) return array("0" => "brak wiadomosci do wyświetlenia");
                else echo "brak wiadomosci do wyświetlenia";
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
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
                    $messageDate = strip_tags((string)$messageDate);
                    $messageContent = strip_tags((string)$messageContent);
                    $messageTitle = strip_tags((string)$messageTitle);
                    $userFirstName = strip_tags((string)$userFirstName);
                    $userSecondName = strip_tags((string)$userSecondName);
                    $userLastName = strip_tags((string)$userLastName);
                    if ($ret) array_push($rarr, getMessageElement($messageContent, $messageDate, $messageTitle, $userFirstName, $userSecondName, $userLastName));
                    else echo getMessageElement($messageContent, $messageDate, $messageTitle, $userFirstName, $userSecondName, $userLastName);
                }
                if ($ret){ 
                    $stmt->close();
                    return $rarr;
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
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
                    echo "<div id='$teacherId'>$teacherFirstName $teacherSecondName $teacherLastName</div>";
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}

function sendMessage($Receivers, $title, $Content)
{
    global $mysqli;
    global $error;

    $sql = "INSERT INTO `messages` (`senderId`, `receiverId`, `messageContent`, `messageTitle`) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("ssss", $senderId, $receiverId, $messageContent, $messageTitle);
        $senderId = $_SESSION["id"];
        $receiverId = "{ \"id\": [";
        foreach ($Receivers as $element) {
            $receiverId .= "" . $element . ",";
        }
        $receiverId .= "]}";
        $messageContent = $Content;
        $messageTitle = $title;
        if ($stmt->execute())
        {
            echo "{\"status\":true}";
        }
        else
        {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}

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


function getTimetable($ret = false, $direction = 0)
{
    global $mysqli;

    //if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($direction == -1)
    {
        $_SESSION['timeTableDate'] = $_SESSION['timeTableDate'] - 1;
    }
    else if ($direction == 1)
    {
        $_SESSION['timeTableDate'] = $_SESSION['timeTableDate'] + 1;
    }
    else if ($direction == 0)
    {
        $_SESSION['timeTableDate'] = 0;
    }
    // }

    $sql = "SELECT classId FROM `users` WHERE users.userId = ?";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["id"];

        if ($stmt->execute())
        {
            $stmt->store_result();
            $stmt->bind_result($classId);
            $stmt->fetch();

            $timeTableDate = $_SESSION['timeTableDate'];

            $sql = "SELECT timetables.subjectId, timetables.teacherId, timetables.classDateStart, timetables.classDateEnd, DATE_FORMAT(timetables.classDateStart, \"%H:%i\") as classStartHour, DATE_FORMAT(timetables.classDateEnd, \"%H:%i\") as classEndHour, timetables.classDescription, timetables.classroom, timetables.obligatory, timetables.substituteTeacherId, timetables.substituteSubjectId, timetables.substituteDescription, timetables.substituteClassroom, timetables.cancelled FROM `timetables` WHERE timetables.classId = $classId AND DATE(timetables.classDateStart) = CURRENT_DATE + INTERVAL $timeTableDate DAY";
            $result = $mysqli->query($sql);

            if ($result !== false && $result->num_rows != 0)
            {
                $i = 1;
                $TEMP = "";
                while ($row = $result->fetch_assoc())
                {
                    
                    if (!isset($row['substitureTeacherId']))
                    {
                        
                        $TEMP .= "<div class='singleLesson'>";
                        $TEMP .= "<h5 class='niumer'>".$i."</h5>";
                        $TEMP.=  "<p class='classStart'>" . $row['classStartHour'] . '</p>';
                        $TEMP .= "<p class='classEnd'>" . $row['classEndHour'] . '</p>';




                        $teacherId = $row['teacherId'];
                        $sql2 = "SELECT userFirstName, userSecondName, userLastName FROM `users` WHERE userId = $teacherId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();




                        $TEMP .= "<p class='ticier'>" . $row2['userFirstName'] . ' ' . $row2['userSecondName'] . ' ' . $row2['userLastName'] . '</p>';
                        $TEMP .= "<p class='peace'>" . $row['classroom'] . '</p>';

                        $subjectId = $row['subjectId'];
                        $sql2 = "SELECT subjectName FROM `subjects` WHERE subjectId = $subjectId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();


                        
                        $TEMP .= "<p class='rzeczpospolitapolska'>Przedmiot: " . $row2['subjectName'] . '</p></div>';
                    }
                    else
                    {
                        $TEMP .= "<div class='singleLesson'>";
                        $TEMP .= "<h5 class='niumer'>".$i."</h5>";
                        $TEMP = '<p>Początek lekcji: ' . $row['classStartHour'] . '</p>';
                        $TEMP .= '<p>Koniec lekcji: ' . $row['classEndHour'] . '</p>';

                        $teacherId = $row['substituteTeacherId'];
                        $sql2 = "SELECT userFirstName, userSecondName, userLastName FROM `users` WHERE userId = $teacherId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();

                        $TEMP .= "<p class='ticier'> Nauczyciel: " . $row2['userFirstName'] . ' ' . $row2['userSecondName'] . ' ' . $row2['userLastName'] . '</p>';
                        $TEMP .= "<p class='peace'>Klasa: " . $row['classroom'] . '</p>';

                        $subjectId = $row['substituteSubjectId'];
                        $sql2 = "SELECT subjectName FROM `subjects` WHERE subjectId = $subjectId";
                        $result2 = $mysqli->query($sql2);
                        $row2 = $result2->fetch_assoc();
                        $TEMP .= $i;
                        $TEMP .= "<p class='rzeczpospolitapolska'> Przedmiot: " . $row2['subjectName'] . '</p></div>';
                    }
                    $i++;
                }
            }
            else
            {
                $TEMP = 'Nie ma informacji';
            }
        }
    }

    $currentDate = date("Y/m/d");
    $date = date("Y-m-d", strtotime($currentDate . $_SESSION['timeTableDate'] . ' days'));
    $TEMP .= "<span id='TDate'><p>" . $date . "</p></span>";
    if ($ret){
        $stmt->close();
        return $TEMP;
    } 
    else echo $TEMP;
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

    if ($result->num_rows != 0)
    {
        while ($row = $result->fetch_assoc())
        {
            echo 'Nauczyciel: ' . $row['userFirstName'] . ' ' . $row['userSecondName'] . ' ' . $row['userLastName'] . ' ' . 'Email: ' . $row['userEmail'] . 'Numer telefonu: ' . $row['userPhoneNumber'] . '<br>';
        }
    }
    else
    {
        $error = $error . "UwU, somethin went wong.";
        echo $error;
    }
}

function getLuckyNumber()
{
    echo rand(0,1000000) == 2005 ? "<h1>Szczęśliwe znaki drogowe</h1>" : "<h1>Szczęśliwe numerki</h1>";
    global $mysqli;

    $sql = "SELECT * FROM `luckynumbers` ORDER BY databaseDate DESC";
    $result = $mysqli->query($sql);

    if ($result->num_rows != 0)
    {
        $row = $result->fetch_assoc();

        if ($row['databaseDate'] != date("Y-m-d"))
        {
            $luckyNumberFirst = rand(1, 15);
            $luckyNumberSecond = rand(16, 30);

            $sql = "INSERT INTO luckynumbers (databaseDate, luckyNumberFirst, luckyNumberSecond) VALUES (CURRENT_DATE, $luckyNumberFirst, $luckyNumberSecond)";
            $mysqli->query($sql);

            echo "<div><h1 class='luckynumber' style='color: white;'>".$luckyNumberFirst ."</h1><h1 class='luckynumber' style='color: white;'>". $luckyNumberSecond."</h1></div>";
        }
        else
        {
            echo "<div><h1 class='luckynumber' style='color: white;'>".$row['luckyNumberFirst']."</h1><h1 class='luckynumber' style='color: white;'>" . $row['luckyNumberSecond']."</h1></div>";
        }
    }
    else
    {
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
    foreach ($ranks as $value)
    {
        if (!in_array($value, $_SESSION["rank"]))
        {
            $t = false;
        }
        else $t = true;
    }
    return $t;
}
function getUserComments($userID)
{
    global $mysqli;
    global $error;

    $sql = "SELECT comments.commentType, comments.commentWeight, comments.commentContent, comments.commentDate, users.userFirstName, users.userSecondName, users.userLastName FROM comments, users WHERE comments.studentId = ? AND users.userId = comments.teacherId ORDER BY comments.commentDate DESC;";

    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $userID;

        if ($stmt->execute())
        {
            $stmt->store_result();
            echo "<div class=\"subjectGradesTitle\">";

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($commentType, $commentWeight, $commentContent, $commentDate, $commentTeacherFirstName, $commentTeacherSecondName, $commentTeacherLastName);
                echo "<div class=\"Uwagititle\"><h1>Uwagi</h1></div>";
                while ($stmt->fetch())
                {
                    if ($commentType == "Uwaga Negatywna")
                    {
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
                    }
                    elseif ($commentType == "Uwaga Pozytywna")
                    {
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
                    }
                    else
                    {
                        {
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
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}


// SELECT subjects.subjectName, users.userFirstName, users.userSecondName, users.userLastName, attendance.subjectNumber, attendance.attendanceState, attendance.attendanceDescription, attendance.attendanceDate, attendance.attendanceExcuse FROM subjects, users, attendance WHERE users.userId = attendance.teacherId AND subjects.subjectId = attendance.subjectId AND attendance.studentId = 3;
function getAttendance($userID,$ret = false,$direction = 0)
{
    global $mysqli;
    global $error;
    $date = date("Y/m/d");
        if ($direction == -1) {
            $_SESSION['attendanceDate'] = $_SESSION['attendanceDate'] - 1;
        } else if ($direction == 1) {
            $_SESSION['attendanceDate'] = $_SESSION['attendanceDate'] + 1;
        } else if ($direction == 0) {
            $_SESSION['attendanceDate'] = 0;
        }
    $attendanceDate = $_SESSION['attendanceDate'];

    $sql = 'SELECT subjects.subjectName, users.userFirstName, users.userSecondName, users.userLastName, attendance.subjectNumber, attendance.attendanceState, attendance.attendanceDescription, attendance.attendanceDate, attendance.attendanceExcuse FROM subjects, users, attendance WHERE users.userId = attendance.teacherId AND subjects.subjectId = attendance.subjectId AND attendance.studentId = ? AND DATE(attendance.attendanceDate) = CURRENT_DATE + INTERVAL ' . strval($attendanceDate) . ' DAY;';
    //$sql = "SELECT timetables.subjectId, timetables.teacherId, timetables.classDateStart, timetables.classDateEnd, DATE_FORMAT(timetables.classDateStart, \"%H:%i\") as classStartHour, DATE_FORMAT(timetables.classDateEnd, \"%H:%i\") as classEndHour, timetables.classDescription, timetables.classroom, timetables.obligatory, timetables.substituteTeacherId, timetables.substituteSubjectId, timetables.substituteDescription, timetables.substituteClassroom, timetables.cancelled FROM `timetables` WHERE timetables.classId = $classId AND DATE(timetables.classDateStart) = CURRENT_DATE + INTERVAL $timeTableDate DAY";



    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $userID;

        if ($stmt->execute())
        {
            $stmt->store_result();

            $TEMP = "";
            if ($stmt->num_rows != 0) {
                $stmt->bind_result($subjectName, $teacherFirstName, $teacherSecondName, $teacherLastName, $subjectNumber, $attendanceState, $attendanceDescription, $attendanceDateDate, $attendanceExcuse);
                
                while ($stmt->fetch()) {

                    if ($attendanceState = "Obecnosc") //$attendanceExcuse
                    {
                        $TEMP .= '<div class="singleAttendance present">
                                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="attendanceType svg-inline--fa fa-check fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="lime" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
                                    <h3 class="subjectName2">' . $subjectName . '<p class="ticzer">' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p> </h3>
                                    <h3 class="subjectNumber2">' . $subjectNumber .". 8:00 - 8-45 dodaj ktos". '</h3>
                                    </div>';
                    } elseif ($attendanceState = "Spoznienie" && isset($attendanceExcuse)) {

                        $TEMP .= '<div class="singleAttendance excusedLateness">
                        <h1 class="subjectNumber">' . $subjectNumber . '</h1>
                        <h3 class="subjectName">' . $subjectName . '</h3>
                        <p class="ticzer">' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2 class="attendanceType>Spóźnienie Usprawiedliwione</h2>
                    </div>';
                    } elseif ($attendanceState = "Spoznienie" && !isset($attendanceExcuse)) {
                        $TEMP .= '<div class="singleAttendance unexcusedLateness">
                        <h1 class="subjectNumber">' . $subjectNumber . '</h1>
                        <h3 class="subjectName">' . $subjectName . '</h3>
                        <p class="ticzer">' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2 class="attendanceType>Spóźnienie Niesuprawiedliwione</h2>
                    </div>';
                    } elseif ($attendanceState = "Nieobecnosc" && isset($attendanceExcuse)) {


                        $TEMP .= '<div class="singleAttendance excusedAbsence">
                        <h1 class="subjectNumber">' . $subjectNumber . '</h1>
                        <h3 class="subjectName">' . $subjectName . '</h3>
                        <p class="ticzer">' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2 class="attendanceType>Nieobecność Usprawiedliwiona</h2>
                    </div>';
                    } elseif ($attendanceState = "Nieobecnosc" && !isset($attendanceExcuse)) {
                        $TEMP .= '<div class="singleAttendance unexcusedAbsence">
                        <h1 class="subjectNumber">' . $subjectNumber . '</h1>
                        <h3 class="subjectName">' . $subjectName . '</h3>
                        <p class="ticzer">' . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . '</p>
                        <h2 class="attendanceType>Nieobecność Niesuprawiedliwiona</h2>
                    </div>';
                    } else {
                        $TEMP .= "<div class='singleAttendance'>
                            <h1>" . $subjectNumber . "</h1>
                            <h3>" . $subjectName . "</h3>
                            <p>" . $teacherFirstName . " " . $teacherSecondName . " " . $teacherLastName . "</p>
                            <h2>' . $attendanceState . '</h2>
                        </div>";
                    }
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            $error = $error . "UwU, somethin went wong.";
            if($ret)return $error;
            else echo $error;
        }
    }
    $currentDate = date("Y/m/d");
    $date = date("Y-m-d", strtotime($currentDate . $_SESSION['attendanceDate'] . ' days'));
    $TEMP .= "<span id='AttendenceDate'><p>" . $date . "</p></span>";
    if ($ret){
        $stmt->close();
        return $TEMP;
    }
    else echo $TEMP;
    //Działa
    $stmt->close();
}

function closestFreeDays()
{
    global $mysqli;
    global $error;

    $sql = "SELECT freedays.freeDayDate, freedays.freeDayReason, freedays.freeDayDescription FROM freedays WHERE freeDayDate > CURRENT_DATE ORDER BY freedays.freeDayDate ASC";

    if ($stmt = $mysqli->prepare($sql))
    {
        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($freeDayDate, $freeDayReason, $freeDayDescription);
                while ($stmt->fetch())
                {
                    echo
                    '<div class="freeDay">
                        <h2>' . $freeDayReason . '</h2>
                        <p class="freeDayDescription">' . $freeDayDescription . '</p>
                        <p>' . $freeDayDate . '</p>
                        
                    </div>';
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
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


    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["classId"];

        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($examDate, $subjectName, $FirstName, $SecondName, $LastName, $examDescription, $examType);

                while ($stmt->fetch())
                {

                    echo '
                    <div class="singleExam">
                        <h2 class="subjectName">' . $subjectName . '</h2>
                        <p class="examDescription">' . $examDescription . '</p>
                        <p>' . $examType . '</p>
                        <p class="examDate">' . $examDate . '</p>
                        
                    </div>
                    ';
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
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


    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("s", $param_id);
        $param_id = $_SESSION["classId"];

        if ($stmt->execute())
        {
            $stmt->store_result();

            if ($stmt->num_rows != 0)
            {
                $stmt->bind_result($subjectName, $FirstName, $SecondName, $LastName, $creationDate, $deadline, $homeworkDescription, $obligatory);

                while ($stmt->fetch())
                {

                    echo '
                    <div class="singleHomework">
                        <h2 class="subjectName">' . $subjectName . '</h2>
                        <p class="homeworkDescription">' . $homeworkDescription . '</p>
                        <p class="creationDate"> Dodano: ' . $creationDate . '</p>
                        <p class="deadline"> Do: ' . $deadline . '</p>
                        <p class="obligatory"> Obowiązkowe? 🡆 ' . $obligatory . '</p>
                    </div>
                    ';
                }
            }
            else
            {
                $error = $error . "UwU, somethin went wong.";
            }
        }
        else
        {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
    $stmt->close();
}


function addFreeDay(
    $freeDayDate,
    $freeDayReason,
    $freeDayDescription
)
{
    $currentDate = date("Y/m/d");
    global $mysqli;
    global $error;
    if (empty($freeDayReason))
    {
        $error = $error . "Powód nie może być pusty!";
    }
    if ($freeDayDate == $currentDate)
    {
        $error = $error . "Nie może to być dzisiejsza data!";
    }
    if (empty($error))
    {
        $sql = "INSERT INTO `freedays` (`freeDayDate`, `freeDayReason`, `freeDayDescription`) VALUES (?, ?, ?)";
        echo "Wysłano do bazy danych.";

        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("sss",$param_date, $param_reason, $param_desc);

            $param_date = strval(date("Y-m-d",strtotime($freeDayDate))); //prawie dziala ale daty nie wysyla
            
            $param_reason = $freeDayReason;
            $param_desc = $freeDayDescription;

            if ($stmt->execute())
            {
                echo "gitówa";
            }
            else
            {
                echo "UwU,somethin went wong!";
            }
            $stmt->close();
        }
    }

    

    
    
    

    echo $error;
    $error = "";

    $mysqli->close();
}

function setExam()
{
    global $mysqli;
    global $error;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "INSERT INTO `exams` (`examDate`, `subjectId`, `teacherId`, `examDescription`, `examType`, `classId`) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssssss", $_POST['examDate'], $_POST['examSubject'], $param_id, $_POST['examDescription'], $_POST['examType'], $_POST['examClass']);
            $param_id = $_SESSION['id'];

            if ($stmt->execute()) {
                echo 'Dodano!';
            } else {
                $error = $error . "Nie powiodło się.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
            echo $error;
        }
    }
}
function setHomework(){
    global $mysqli;
global $error;

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $sql = "INSERT INTO `homework` (`deadline`, `subjectId`, `teacherId`, `creationDate`, `homeworkDescription`, `obligatory` ,`classId` ) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?)";


    if ($stmt = $mysqli->prepare($sql))
    {
        $stmt->bind_param("ssssss", $_POST['homeworkDeadline'], $_POST['homeworkSubject'], $param_id, $_POST['homeworkDescription'], $_POST['obligatory'], $_POST['homeworkClass']);
        $param_id = $_SESSION['id'];

        if ($stmt->execute())
        {
            echo 'Dodano!';
        }
        else
        {
            $error = $error . "Nie powiodło się.";
        }
    }
    else
    {
        $error = $error . "UwU, somethin went wong.";
        echo $error;
    }
}
}
function getTeachersSubjects()
{
    global $mysqli;

            $sql = "SELECT * FROM `subjects`";
            $result = $mysqli->query($sql);


            while ($row = $result->fetch_assoc())
            {
                $obj = json_decode($row['teacherId']);
                echo json_encode($obj) . " ";
                if (isset($obj->id) && in_array($_SESSION["id"], $obj->id))
                {
                    echo '<option value="' . $row['subjectId'] . '">' . $row['subjectName'] . '</option>';
                } else {
                    echo 'Nie!';
                }
            }

    
}

function getTeachersClasses()
{
    global $mysqli;

    $sql = "SELECT classId, classGrade, classLetter, classType FROM `classes` ";
    $result = $mysqli->query($sql);

    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['classId'] . '">' . $row['classGrade'] . $row['classLetter'] . ' ' . $row['classType'] . '</option>';
    }
}

function setComment()
{
    global $mysqli;
    global $error;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "INSERT INTO `comments` (`commentId`, `commentType`, `commentWeight`, `commentContent`, `commentDate`, `teacherId`, `studentId`) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
        $currentDate = date("Y/m/d H:i:s");

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sissii", $_POST['commentType'], $_POST['commentWeight'], $_POST['commentContent'], $currentDate, $param_id, $_POST['studentId']);
            $param_id = $_SESSION['id'];

            if ($stmt->execute()) {
                echo 'Dodano!';
            } else {
                $error = $error . "Nie powiodło się.";
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
        }
        echo $error;
    }
}

function getTeachersStudents()
{
    global $mysqli;

    $sql = "SELECT * FROM `users` ORDER BY userLastName ASC";
    $result = $mysqli->query($sql);

    echo 'test';

    while ($row = $result->fetch_assoc()) {
        $obj = json_decode($row['userRank']);
        if (in_array("uczen", $obj->{'rank'})) {
            echo '<option value="' . $row['userId'] . '">' . $row['userFirstName'] . ' ' . $row['userSecondName'] . ' ' . $row['userLastName'] . ' ' . '</option>';
        } else {
            echo 'Nie!';
        }
    }
}

function editSchoolInformation()
{
    global $mysqli;
    global $error;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "UPDATE `schoolinformation` SET `schoolName` = ?, `schoolAddress` = ?, `schoolPhoneNumber` = ?, `schoolPrincipal` = ?, `schoolEndYear` = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('sssss', $_POST['schoolName'], $_POST['schoolAddress'], $_POST['schoolPhoneNumber'], $_POST['schoolPrincipal'], $_POST['schoolEndYear']);

            if ($stmt->execute()) {
                echo 'Zmieniono!';
            } else {
                echo 'Nie działa QwQ';
            }
        } else {
            $error = $error . "UwU, somethin went wong.";
        }
    }

    $sql = "SELECT * FROM `schoolinformation`";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    echo '<form method="POST"><input type="text" name="schoolName" value="' . $row['schoolName'] . '" placeholder="' . $row['schoolName'] . '">
    <input type="text" name="schoolAddress" value="' . $row['schoolAddress'] . '" placeholder="' . $row['schoolAddress'] . '">
    <input type="text" name="schoolPhoneNumber" value="' . $row['schoolPhoneNumber'] . '" placeholder="' . $row['schoolPhoneNumber'] . '">
    <input type="text" name="schoolPrincipal" value="' . $row['schoolPrincipal'] . '" placeholder="' . $row['schoolPrincipal'] . '">
    <input type="date" name="schoolEndYear" value="' . $row['schoolEndYear'] . '" placeholder="' . $row['schoolEndYear'] . '">
    <input type="submit" name="submit" value="Edytuj"></form>';
}

// function getClassSubjectGrades() {
//     global $mysqli;

//     $sql="SELECT users.userId, users.userFirstName, users.userSecondName, users.userLastName, grades.gradeScale, subjects.subjectName, gradecolumns.columnPosition FROM `grades` NATURAL JOIN `users` NATURAL JOIN `gradecolumns`, `subjects` WHERE subjects.subjectId = grades.subjectId AND users.userId = grades.studentId ORDER BY users.userLastName ASC, gradecolumns.columnPosition ASC ";

// }