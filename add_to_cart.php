<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
if (isset($_POST) & !empty($_POST)) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $av_id = $_POST['av_id'];
    $count = count($av_id);
    $av_id = implode(', ', $av_id);
}

if (isset($_GET) & !empty($_GET)) {
    $product_id = $_GET['product_id'];
    $quantity = $_GET['quantity'];
    $av_id = $_GET['av_id'];
    $av_id = explode(",", $av_id);
    $count = count($av_id);
    $av_id = implode(', ', $av_id);
}

$sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id IN ($av_id) GROUP BY variant_attribute_value_id HAVING COUNT(variant_attribute_value_id) = '$count'";
$res = mysqli_query($conn, $sql);
$r = mysqli_fetch_assoc($res);
$variant_attribute_value_id = $r['variant_attribute_value_id'];

$customer_id = $_COOKIE['user'];
// check if product already exists in cart
$sql = "SELECT * FROM carts WHERE product_id='$product_id' AND customer_id='$customer_id' AND variant_attribute_value_id='$variant_attribute_value_id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if ($count == 1) {
    // update quantity
    $sql = "UPDATE carts SET quantity=quantity+$quantity WHERE product_id='$product_id' AND customer_id='$customer_id'";
    mysqli_query($conn, $sql);
} else {
    // insert into cart
    $sql = "INSERT INTO carts (customer_id, product_id, quantity, variant_attribute_value_id) VALUES ('$customer_id', '$product_id', '$quantity', '$variant_attribute_value_id')";
    mysqli_query($conn, $sql);
}

if (isset($_GET) & !empty($_GET)) {
    header("Location: cart.php");
}
