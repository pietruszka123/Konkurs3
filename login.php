<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Document</title>
</head>

<body>
    <?php
    include_once("header.php");
    ?>
    <img class="bombel" src="bombel.png" alt=""Style="left: 15vw; bottom: 4.5vw;">
    <img class="heksagon"src="heksagon.svg" alt=""style="bottom: 3.2vw; right: -7.48vw;transform:rotate(120deg);">
    <img class="gwiazdka"src="gwiazdka.svg" alt=""style="top: 5vw; right:20vw;transform:rotate(45deg);">
        <div class="container">
            <div class="logowanie">  
                    <h1 class="h1Default">Logowanie</h1>
                    <h2>Proszę podać e-mail i hasło</h2>
                    <div class="logowanieDiv">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <label for="email">E-mail:</label>
                            <input class="input" type="text" name="email" required><br>
                            <label for="Password">Hasło:</label>
                            <input type="password" name="password" required><br>
                         </div>
                             <div class="log">
                                 <input type="submit" value="Zaloguj">
                             </div>
                     </form>
                
            </div>
        </div>
    </div>
    </div>

</body>

</html>


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
        $sql = "SELECT userId, userEmail, userPassword, userRank FROM users WHERE userEmail = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($userId, $userEmail, $userPasswordHashed, $userRank); //Nazwa z sqla

                    if ($stmt->fetch()) {
                        if (password_verify($password, $userPasswordHashed)) {
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["id"] = $userId;
                            $_SESSION["email"] = $userEmail;
                            $obj = json_decode($userRank);
                            $_SESSION["rank"] = $obj->{'rank'};


                            if (in_array("uczen", $_SESSION["rank"])) {
                                header("location: member.php");
                            } else {
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