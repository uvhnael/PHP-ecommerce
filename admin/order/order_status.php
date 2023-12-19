<?php
    session_start();
    include("../includes/db.php");
    if(!isset($_SESSION['email']) & empty($_SESSION['email'])){
        header('location: login.php');
    }
    if(isset($_GET) & !empty($_GET)){
        $id = $_GET['id'];
        $status = $_GET['status'];
        if($status == 2){
            $order_approved_at = date("Y-m-d H:i:s");
            $sql = "UPDATE orders SET order_status_id='$status', order_approved_at='$order_approved_at' WHERE id='$id'";
        }
        else
        {
            $sql = "UPDATE orders SET order_status_id='$status' WHERE id='$id'";
        }
        $result = mysqli_query($conn, $sql);
        if($result){
            header("location: orders.php");
        }else{
            $fmsg = "Failed to Update Order Status";
        }  
    }
?>