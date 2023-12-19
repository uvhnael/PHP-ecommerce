<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 2) {
        header('location: ../index.php');
    }
}
if (isset($_GET) & !empty($_GET)) {
    $product_id = $_GET['product_id'];
    $gallery_id = $_GET['gallery_id'];

    $sql = "SELECT * FROM `galleries` WHERE product_id='$product_id' AND thumbnail='1'";
    $result = mysqli_query($conn, $sql);
    $r = mysqli_fetch_assoc($result);
    $thumbnail = $r['id'];
    $sql = "UPDATE `galleries` SET thumbnail='0' WHERE id='$thumbnail'";
    $result = mysqli_query($conn, $sql);

    $sql = "UPDATE `galleries` SET thumbnail='1' WHERE id='$gallery_id'";
    $result = mysqli_query($conn, $sql);

    header("location: edit_product.php?id=$product_id");
}
