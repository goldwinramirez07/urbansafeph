<?php
if (isset($_POST['submit'])) {
    header("Location: emergency1.php");
}
else if (isset($_POST['submit-fire'])) {
    header("Location: fire-report1.php");
}
else if (isset($_POST['submit-flood'])) {
    header("Location: flood-report1.php");
}
else if (isset($_POST['submit-crime'])) {
    header("Location: crime-report1.php");
}
else if (isset($_POST['submit-incident'])) {
    header("Location: incident-report1.php");
}
?>