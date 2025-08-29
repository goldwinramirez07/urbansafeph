<?php
if (isset($_POST['seemed'])) {
    header("Location: Incident/notimed.php");
}
else if (isset($_POST['seepolice'])) {
    header("Location: Crime/notipolice.php");
}
else if (isset($_POST['seefire'])) {
    header("Location: Fire/notifire.php");
}
else if (isset($_POST['seeflood'])) {
    header("Location: Flood/notiflood.php");
}
?>