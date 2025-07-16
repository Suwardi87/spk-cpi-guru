<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // relatif terhadap file ini
    exit;
}

header("Location: pages/dashboard.php");
exit;
?>
