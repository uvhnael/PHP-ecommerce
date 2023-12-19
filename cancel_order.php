<?php
    if(!isset($_COOKIE['user'])){
        header("Location: login.php");
    }
    include("includes/db.php");
    if(isset($_GET) & !empty($_GET)){
        $order_id = $_GET['order_id'];
    }
    else{
        header("Location: order.php");
    }

    include("includes/header.php");

    $sql = "UPDATE orders SET order_status_id='4' WHERE id='$order_id'";
    mysqli_query($conn, $sql);

    header("Location: order.php");
?>