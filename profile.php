<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");

$customer_id = $_COOKIE['user'];

if (isset($_POST) && !empty($_POST)) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    $sql = "UPDATE customers SET first_name='$first_name', last_name='$last_name', phone_number='$phone_number', email='$email' WHERE id='$customer_id'";
    mysqli_query($conn, $sql);
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
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name"
                        value="<?php echo $customer['first_name']; ?>" />
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name"
                        value="<?php echo $customer['last_name']; ?>" />
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number"
                        placeholder="Phone Number" value="<?php echo $customer['phone_number']; ?>" />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email"
                        value="<?php echo $customer['email']; ?>" />
                </div>
                </br>
                <div class="d-grid gap-2 col-4 mx-auto">
                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?
include("includes/footer.php");
?>