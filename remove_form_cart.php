<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
if (isset($_GET) & !empty($_GET)) {
    $product_id = $_GET['product_id'];
    $customer_id = $_COOKIE['user'];

    $sql = "DELETE FROM carts WHERE product_id='$product_id' AND customer_id='$customer_id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: cart.php");
    }
}
