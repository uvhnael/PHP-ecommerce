<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Merchandize Manager') {
        header('location: ../index.php');
    }
}
if (isset($_POST) & !empty($_POST)) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $coupon_desription = mysqli_real_escape_string($conn, $_POST['coupon_desription']);
    $discount_value = mysqli_real_escape_string($conn, $_POST['discount_value']);
    $discount_type = mysqli_real_escape_string($conn, $_POST['discount_type']);
    $max_usage = mysqli_real_escape_string($conn, $_POST['max_usage']);
    $coupon_start_date = mysqli_real_escape_string($conn, $_POST['coupon_start_date']);
    $coupon_end_date = mysqli_real_escape_string($conn, $_POST['coupon_end_date']);
    $created_by = $_SESSION['id'];

    $sql = "INSERT INTO `coupons`(`code`, `coupon_desription`, `discount_value`, `discount_type`, `times_used`, `max_usage`, `coupon_start_date`, `coupon_end_date`,`created_by`) VALUES ('$code','$coupon_desription','$discount_value','$discount_type','0','$max_usage','$coupon_start_date','$coupon_end_date','$created_by')";
    $res = mysqli_query($conn, $sql);

    header('location: coupons.php');
}

include('../includes/header.php');
include('../includes/sidebar.php');
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Add Coupon</h2>
            <form method="post" class="form-horizontal myaccount" action="">
                <div class="form-group">
                    <label for="code" class="col-sm-2 control-label">Coupon Code</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="coupon_desription" class="col-sm-2 control-label">Coupon Description</label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" id="coupon_desription" name="coupon_desription" required>
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount_value" class="col-sm-2 control-label">Discount value</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="discount_value" name="discount_value" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount_type" class="col-sm-2 control-label">Discount Type</label>
                    <div class="col-sm-10">
                        <select name="discount_type" class="form-control">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                            <option value="free_shipping">Free Shipping</option>
                            <option value="product_percentage">Product Percentage</option>
                            <option value="product_fixed">Product Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="max_usage" class="col-sm-2 control-label">Max Usage</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="max_usage" name="max_usage" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="coupon_start_date" class="col-sm-2 control-label">Coupon Start Date</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" id="coupon_start_date" name="coupon_start_date" format="Y-m-d H:i:s" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="coupon_end_date" class="col-sm-2 control-label">Coupon End Date</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" class="form-control" id="coupon_end_date" name="coupon_end_date" format="Y-m-d H:i:s" required>
                    </div>
                </div>
                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function generateRandomString(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            result += characters.charAt(randomIndex);
        }
        return result;
    }


    document.getElementById('code').value = generateRandomString(12);
</script>
<?php include('../includes/footer.php'); ?>