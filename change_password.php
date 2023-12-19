<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");

$customer_id = $_COOKIE['user'];

if (isset($_POST) && !empty($_POST)) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password != $confirm_password) {
        $fmsg = "</br><div class='alert alert-danger'>Password do not match</div>";
    } else {
        $sql = "UPDATE customers SET password='$password' WHERE id='$customer_id'";
        mysqli_query($conn, $sql);
        header("Location: profile.php");
    }
}

$sql = "SELECT * FROM customers WHERE id='$customer_id'";
$result = mysqli_query($conn, $sql);
$customer = mysqli_fetch_assoc($result);
include("includes/header.php");
include("includes/navbar.php");
?>
<div class="container section-padding">
    <div class="row justify-content-md-center">
        <?php include("includes/sidebar.php"); ?>
        <div class="col-md-6">
            <h3>Profile</h3>
            <form method="post">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" class="form-control" id="password" />
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" />
                </div>
                <?php
                if (isset($fmsg)) {
                    echo $fmsg;
                }
                ?>
                </br>

            </form>
            <div class="d-grid gap-2 col-4 mx-auto">
                <button type="submit" class="btn btn-primary ">Update</button>
            </div>
        </div>
    </div>
</div>

<?
include("includes/footer.php");
?>