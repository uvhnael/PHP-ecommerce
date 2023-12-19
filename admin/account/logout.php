<?php
    // logout session
    session_start();
    include_once('../includes/db.php');

    $staff_id = $_SESSION['id'];

    $sql = "UPDATE staffs SET active=0 WHERE id='$staff_id'";
    mysqli_query($conn, $sql);

    session_destroy();
    
    header("location: login.php");


?>