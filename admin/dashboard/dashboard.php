<?php
session_start();
include_once('../includes/db.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<?php include('../includes/footer.php'); ?>