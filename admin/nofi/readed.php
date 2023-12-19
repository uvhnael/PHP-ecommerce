<?php
include_once('../includes/db.php');
$notification_id = $_GET['nofi_id'];
$order_id = $_GET['order_id'];
$receive_at = date("Y-m-d H:i:s");
$sql = "UPDATE notifications SET seen=1, receive_at = '$receive_at'  WHERE id='$notification_id'";
mysqli_query($conn, $sql);

header('location: ../order/order_details.php?id=' . $order_id . '');
