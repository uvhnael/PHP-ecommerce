<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Staff Manager') {
        header('location: ../index.php');
    }
}
include_once('../includes/db.php');

if (isset($_POST) & !empty($_POST)) {
    $staff_id = $_POST['staff_id'];
    $role_id = $_POST['role_id'];

    $sql = "UPDATE staff_roles SET role_id=$role_id WHERE staff_id=$staff_id";
    $res = mysqli_query($conn, $sql);
}
