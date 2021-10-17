<?php
include_once("header.php");
include_once("views/t_header.php");
?>
<style>
    body {
        display: flexbox;
    }
    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 100%;
        height: 20%;
    }
    li {
        display: inline-flex;
        width: 20%;
        height: 100%;
        background-color: indigo;
    }
</style>
<ul class="HorizontalNavBar">
    <li id="messages">wiadomosci</li>
    <li id="grades">oceny</li>
    <li id="?">jakies inne rzeczy</li>
    <li id="??">jakies inne rzeczy 2.0</li>
</ul>
<iframe src="views/grades.php" frameborder="0"></iframe>
<script src="js/teacher.js"></script>
<?php
include_once("footer.php");
?>