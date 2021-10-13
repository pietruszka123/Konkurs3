<?php
include_once("header.php");

?>

<div class="grades">
    <div class="subjectGradesTable">
        <?php
        getUserGrades();

        ?>

    </div>
</div>
<div class="daysUntilEndOfYear">
    <?php
    getDaysUntilEndOfYear()
    ?>
</div>
<script>
    /**skomentyj aby wyłączyć */
    $.ajax({
        type: "post",
        url: "/api.php/getEndTime",
        contentType: "application/json; charset=utf-8",
        success: function(response) {
            var endDate = new Date(response.message*1000);
            setInterval(() => {
                const today = new Date();
                const days = parseInt((endDate - today) / (1000 * 60 * 60 * 24));
                const hours = parseInt(Math.abs(endDate - today) / (1000 * 60 * 60) % 24);
                const minutes = parseInt(Math.abs(endDate.getTime() - today.getTime()) / (1000 * 60) % 60);
                const seconds = parseInt(Math.abs(endDate.getTime() - today.getTime()) / (1000) % 60);
                $(".daysUntilEndOfYear").html(`DNI DO KONCA ROKU: ${days}<br>GODZINY DO KONCA ROKU: ${hours + 24 * days}<br>;MINUTY DO KONCA ROKU: ${minutes + hours * 60 + 60 * 24 * days}<br>;SEKUNDY DO KONCA ROKU: ${seconds + 60 * minutes + 60 * 60 * hours + 60 * 60 * 24 * days}<br>`)
            }, 1000)
        }
    });
</script>


<?php
include_once("footer.php");
?>