<?php
// Server-side script (PHP)
session_start();
if (isset($_SESSION["userDetails"])) {
    $userDetails = $_SESSION["userDetails"];
    echo $userDetails["email"];
} else {
    echo "no email";
}
?>