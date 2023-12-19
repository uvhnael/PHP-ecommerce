<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}
include_once('../includes/db.php');

if (isset($_POST) & !empty($_POST)) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $your_password = $_POST['your_password'];

    $staff_id = $_SESSION['id'];
    $sql = "SELECT * FROM staffs WHERE id=$staff_id";
    $res = mysqli_query($conn, $sql);
    $staff = mysqli_fetch_assoc($res);

    if (md5($your_password) != $staff['password']) {
        $fmsg = "Your password is wrong";
    } else {

        if ($password == $confirm_password) {
            $password = md5($password);
            $sql = "UPDATE staffs SET password='$password' WHERE id=" . $_SESSION['id'];
            $res = mysqli_query($conn, $sql);
            if ($res) {
                header("Location: profile.php");
            }
        } else {
            $fmsg = "Password not match";
        }
    }
}

include '../includes/header.php';
?>

<div class="container section-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h2>Change Password</h2>
            <form method='post' enctype="multipart/form-data">
                <div class="form-group">
                    <label for="your_password">Your Password</label>
                    <input type="password" name="your_password" class="form-control" id="your_password" />
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" class="form-control" id="password" />
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" />
                </div>
                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>