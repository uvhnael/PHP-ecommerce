<?php
    include("../includes/db.php");

    if(isset($_GET) & !empty($_GET)){
        $id = $_GET['id'];
        $status = $_GET['status'];
        if($status == 2){
            $order_delivered_carrier_date = date("Y-m-d H:i:s");
            $sql = "UPDATE orders SET order_delivered_carrier_date='$order_delivered_carrier_date' WHERE id='$id'";
        }
        else
        {
            $order_delivered_customer_date = date("Y-m-d H:i:s");
            $sql = "UPDATE orders SET order_delivered_customer_date='$order_delivered_customer_date', order_status_id ='3' WHERE id='$id'";
        }
        $result = mysqli_query($conn, $sql);
        if($result){
            header("location: index.php");
        }else{
            $fmsg = "Failed to Update Order Status";
        }  
    }

?>