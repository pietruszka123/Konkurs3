<?php
include_once("t_header.php");
?>


<form method="post">
    <select name="coDodac" onchange="this.form.submit()">
    <option value="">Wybierz co chcesz dodac</option>


    <?php
        $_SESSION["coDodac"] = $_POST["coDodac"];

        ?>
        <?php
            if (isset($_SESSION["coDodac"]) && $_SESSION["coDodac"] == "test")
            {
                echo '<option selected value="test">Test</option>
                <option value="zadanieDomowe">Zadanie domowe</option>';

                
            }
            elseif (isset($_SESSION["coDodac"]) && $_SESSION["coDodac"] == "zadanieDomowe")
            {
                echo '<option value="test">Test</option>
                <option selected value="zadanieDomowe">Zadanie domowe</option>';
            }
            else
            {
                echo '<option value="test">Test</option>
                <option value="zadanieDomowe">Zadanie domowe</option>';
            }
        

        ?>
    </select>
</form>
<?php 
if (isset($_SESSION["coDodac"]) && $_SESSION["coDodac"] == "test")
{
    echo '<iframe src="exam.php" frameborder="0" class="inframe element"></iframe>';

    
}
elseif (isset($_SESSION["coDodac"]) && $_SESSION["coDodac"] == "zadanieDomowe")
{
    echo '<iframe src="home.php" frameborder="0" class="inframe element"></iframe>';
}
?>




<?php
include_once("../footer.php");
?>