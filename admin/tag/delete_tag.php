<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Merchandize Manager') {
        header('location: ../index.php');
    }
}
if (isset($_GET) & !empty($_GET)) {
    $tag_id = $_GET['tag_id'];

    if (isset($_GET['product_id'])) {
        $product_id = $_GET['product_id'];
        $sql = "DELETE FROM `product_tags` WHERE product_id='$product_id' AND tag_id='$tag_id'";
        $result = mysqli_query($conn, $sql);
        header("location: edit_tags.php?product_id=$product_id");
    } else {
        $sql = "DELETE FROM `tags` WHERE id='$tag_id'";
        $result = mysqli_query($conn, $sql);
        header("location: tags.php");
    }
}
