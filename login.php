<?php
include_once("header.php");
?>
<style> 
body{
display: flex;
    flex-wrap: wrap;
    justify-content: center; }
    </style>
<img class="bombel" src="img/bombel.png" alt="" Style="left: 15vw; bottom: 4.5vw;">
<img class="heksagon" src="img/heksagon.svg" alt="" style="bottom: 3.2vw; right: -7.48vw;transform:rotate(120deg);">
<img class="gwiazdka" src="img/gwiazdka.svg" alt="" style="top: 5vw; right:20vw;transform:rotate(45deg);">
    <div class="loginPanel element">
        <h1 class="h1Default">Logowanie</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">E-mail:</label>
            <input class="input" type="email" name="email" required><br>
            <label for="Password">Hasło:</label>
            <input type="password" name="password" required><br>
            <input type="submit" value="Zaloguj">
            <h2 style="text-align:center;padding-top:1vw;">Nie masz jeszcze konta?</h2> <a href="register.php" class="logReg">
                <h2>Zarejestruj się, teraz!</h2>
            </a>
        </form>

    </div>




<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $error = $error . "Email can't be empty!";
    }

    if (empty($password)) {
        $error = $error . "Password can't be empty!";
    }

    if (empty($error)) {
        $sql = "SELECT userId, userEmail, userPassword, userRank, classId FROM users WHERE userEmail = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($userId, $userEmail, $userPasswordHashed, $userRank, $classId); //Nazwa z sqla

                    if ($stmt->fetch()) {
                        if (password_verify($password, $userPasswordHashed)) {
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["id"] = $userId;
                            $_SESSION["email"] = $userEmail;
                            $_SESSION["classId"] = $classId;
                            $obj = json_decode($userRank);
                            $_SESSION["rank"] = $obj->{'rank'};
                            if (in_array("uczen", $_SESSION["rank"])) {
                                header("location: member.php");
                            } else {
                                header("location: teacher.php");
                                echo "nauczyciel";
                            }
                        } else {
                            $error = $error . "Invalid username or password.";
                        }
                    }
                } else {
                    $error = $error . "Invalid username or password.";
                }
            } else {
                echo "UwU, somethin went wong.";
            }
            $stmt->close();
        }
    }

    echo $error;
    $error = "";

    $mysqli->close();
}

include_once("footer.php");
?>