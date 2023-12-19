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

    $sql = "SELECT * FROM `galleries` WHERE product_id='$product_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        header("location: edit_product.php?id=$product_id");
    } else {
        $sql = "DELETE FROM `galleries` WHERE id='$gallery_id'";
        $result = mysqli_query($conn, $sql);

        $sql = "SELECT * FROM `galleries` WHERE product_id='$product_id'";
        $result = mysqli_query($conn, $sql);
        $gallery = mysqli_fetch_assoc($result);
        if ($gallery['thumbnail'] != '1') {
            $sql = "UPDATE `galleries` SET thumbnail='1' WHERE id={$gallery['id']}";
            $result = mysqli_query($conn, $sql);
        }
        header("location: edit_product.php?id=$product_id");
    }
}
