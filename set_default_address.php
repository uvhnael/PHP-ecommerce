<?php

    if(!isset($_COOKIE['user'])){
        header("Location: login.php");
    }
    include("includes/db.php");

    $customer_id = $_COOKIE['user'];

    if(isset($_GET) & !empty($_GET)){
        $address_id = $_GET['address_id'];
    }
    else{
        header("Location: address.php");
    }

    $sql = "UPDATE customers_addresses SET is_default=0 WHERE customer_id='$customer_id' AND is_default=1";
    mysqli_query($conn, $sql);

    $sql = "UPDATE customers_addresses SET is_default=1 WHERE customer_id='$customer_id' AND id='$address_id'";
    mysqli_query($conn, $sql);

    header("Location: address.php");
?>