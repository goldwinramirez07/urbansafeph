<?php
if (isset($_POST['submit'])) {
    header("Location: ../index.php");
}
else if (isset($_POST['backmed']))
{
    header("Location: medical.php");
}
else if (isset($_POST['backpolice']))
{
    header("Location: police.php");
}
else if (isset($_POST['backfire']))
{
    header("Location: fire.php");
}
else if (isset($_POST['backreport']))
{
    header("Location: Guest/report.php");
}
else if (isset($_POST['backreport1']))
{
    header("Location: Member/report1.php");
}
?>