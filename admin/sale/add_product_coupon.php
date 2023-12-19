<?php
session_start();
include_once('../includes/db.php');
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Merchandize Manager') {
        header('location: ../index.php');
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';

if (isset($_POST) & !empty($_POST)) {
    $product_id = $_POST['product_id'];
    $coupon_id = $_POST['coupon_id'];

    $sql = "INSERT INTO product_coupons (product_id, coupon_id) VALUES ('$product_id', '$coupon_id')";
    mysqli_query($conn, $sql);

    header('location: product_coupon.php');
}

?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">

            <h2>Add Product Coupon</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="from-group">
                    <label for="product_id">Product</label>
                    <select name="product_id" class="form-control">
                        <option value="">Select Product</option>
                        <?php
                        $sql = "SELECT * FROM products";
                        $res = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($res)) {
                        ?>
                            <option value="<?php echo $r['id']; ?>">#<?php echo $r['id'] . " " . $r['product_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="from-group">
                    <label for="coupon_id">Coupon</label>
                    <select name="coupon_id" class="form-control">
                        <option value="">Select Coupon</option>
                        <?php
                        $sql = "SELECT * FROM coupons WHERE discount_type='product_percentage' OR discount_type='product_fixed'";
                        $res = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($res)) {
                        ?>
                            <option value="<?php echo $r['id']; ?>">#<?php echo $r['id'] . " " . $r['code']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
                </from>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>