<?php
if (isset($_POST['submit'])) {
    header("Location: emergency.php");
}
else if (isset($_POST['submit-fire'])) {
    header("Location: fire-report.php");
}
else if (isset($_POST['submit-flood'])) {
    header("Location: flood-report.php");
}
else if (isset($_POST['submit-crime'])) {
    header("Location: crime-report.php");
}
else if (isset($_POST['submit-incident'])) {
    header("Location: incident-report.php");
}
?>