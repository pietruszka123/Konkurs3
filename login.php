<?php
include_once("header.php");
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="Zaloguj!">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email))
    {
        $error = $error . "Email can't be empty!";
    }

    if (empty($password))
    {
        $error = $error . "Password can't be empty!";
    }

    if (empty($error))
    {
        $sql = "SELECT userId, userEmail, userPassword, userRank FROM users WHERE userEmail = ?";

        if ($stmt = $mysqli->prepare($sql))
        {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute())
            {
                $stmt->store_result();

                if ($stmt->num_rows == 1)
                {
                    $stmt->bind_result($userId, $userEmail, $userPasswordHashed, $userRank); //Nazwa z sqla

                    if ($stmt->fetch())
                    {
                        if (password_verify($password, $userPasswordHashed))
                        {
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["id"] = $userId;
                            $_SESSION["email"] = $userEmail;
                            $obj = json_decode($userRank);
                            $_SESSION["rank"] = $obj->{'rank'};


                            if (in_array("uczen", $_SESSION["rank"]))
                            {
                                header("location: member.php");
                            }
                            else
                            {
                                echo "nauczyciel";
                            }
                        }
                        else
                        {
                            $error = $error . "Invalid username or password.";
                        }
                    }
                }
                else
                {
                    $error = $error . "Invalid username or password.";
                }
            }
            else
            {
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