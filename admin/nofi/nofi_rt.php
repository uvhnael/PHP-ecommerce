<?php
    include_once('../includes/db.php');
    session_start();
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM notifications WHERE account_id='$id' AND seen=0";
    $result = mysqli_query($conn, $sql);
    $notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $notifications_count = mysqli_num_rows($result);
    
    echo $notifications_count;

    $conn->close();
?>