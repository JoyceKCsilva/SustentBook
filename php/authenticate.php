<?php
session_start();

if (!isset($_SESSION['USR_ID'])) {
    header('Location: login.php');
    exit();
}
?>
