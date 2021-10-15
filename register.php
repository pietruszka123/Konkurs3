<?php
include_once("header.php");
?>
<link href="http://fonts.cdnfonts.com/css/honey-script" rel="stylesheet">
<img class="gwiazdka" src="img/gwiazdka.svg" alt="" style="top: 5vw; left:20vw;transform:rotate(120deg);">
<img class="bombel" src="img/bombel.png" alt="" style="right: 18vw; bottom: 8.5vw;transform:rotate(60deg)">
<img class="heksagon" src="img/heksagon.svg" alt="" style="left: 4vw;bottom: -4vw;transform:rotate(120deg);">
<div class="logowanie">
    <h1 class="h1Default">Rejestracja</h1>
    <h2>Proszę podać e-mail, hasło i potwierdź hasło</h2>
    <div class="logowanieDiv">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">E-mail:</label>
            <input class="input" type="text" name="email" required><br>
            <label for="Password">Hasło:</label>
            <input type="password" name="password" required><br>
            <label for="Password">Powtórz hasło:</label>
            <input type="password" name="passwordConfirm" required><br>
    </div>
    <div class="log">
        <input type="submit" value="Zarejestruj">
    </div>
    </form>
    <h2 style="text-align:center;padding-top:1vw;padding-bottom:0.4vw;">Zarejestrowany?</h2>
    <a href="login.php" class="logReg">
        <h2>Zaloguj się :D</h2>
    </a>

</div>



<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $passwordConfirm = trim($_POST['passwordConfirm']);

    if (empty($email))
    {
        $error = $error . "Email can't be empty!";
    }
    elseif (!preg_match('/^[a-zA-Z0-9\-\._]+@[a-zA-Z0-9\-\._]+$/', $email))
    {
        $error = $error . "Email is in the wrong format.";
    }
    else
    {
        $sql = "SELECT userId, userPassword FROM users WHERE userEmail = ?";

        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;
            if ($stmt->execute())
            {
                $stmt->store_result();
                $stmt->bind_result($emailFromDatabase, $passwordFromDatabase);

                if ($stmt->num_rows == 1)
                {
                    $emailDone = $email;
                }
                else
                {
                    $error = $error . "Email does not exist in the database.";
                }
            }
            else
            {
                echo "UwU, somethin went wong!";
            }

            while ($stmt->fetch())
            {
                $pFD = $passwordFromDatabase;
            }

            $stmt->close();
        }
    }

    if (empty($password))
    {
        $error = $error . "Password can't be empty!";
    }
    elseif (!preg_match('/^[a-zA-Z0-9\-\._!@#$%^&*]{6,64}$/', $password))
    {
        $error = $error . "Password is in the wrong format.";
    }
    elseif (empty($passwordConfirm))
    {
        $error = $error . "Password can't be empty!";
    }
    elseif (!preg_match('/^[a-zA-Z0-9\-\._!@#$%^&*]{6,64}$/', $passwordConfirm))
    {
        $error = $error . "Password is in the wrong format.";
    }
    elseif ($password != $passwordConfirm)
    {
        $error = $error . "Passwords don't match.";
    }
    elseif ($pFD != "empty")
    {
        $error = $error . "This account is already registered.";
    }

    if (empty($error))
    {
        $sql = "UPDATE users SET userPassword = ? WHERE userEmail = ?";
        echo "Wysłano do bazy danych.";

        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("ss", $param_password, $param_email);

            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;

            if ($stmt->execute())
            {
                header("location: login.php");
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

include_once("footer.php");
?>