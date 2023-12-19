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

?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">

            <h2>Product Coupon</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Coupon Code</th>
                        <th>Discount</th>
                        <th>Product Apllied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM coupons INNER JOIN product_coupons ON product_coupons.coupon_id = coupons.id GROUP BY product_coupons.coupon_id";
                    $res = mysqli_query($conn, $sql);

                    while ($r = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo $r['code']; ?></td>
                            <td><?php echo $r['discount_value']; ?></td>
                            <td>
                                <select class="form-control">
                                    <?php
                                    $sql = "SELECT * FROM product_coupons INNER JOIN products ON product_coupons.product_id = products.id WHERE product_coupons.coupon_id = {$r['id']}";
                                    $res1 = mysqli_query($conn, $sql);
                                    while ($r1 = mysqli_fetch_assoc($res1)) {
                                    ?>
                                        <option value="<?php echo $r1['id']; ?>">
                                            <?php echo "#" . $r1['id'] . " " . $r1['product_name']; ?></option>
                                    <?php } ?>
                                </select>

                            </td>
                            <td>
                                <a class="btn btn-primary" href="edit_product_coupon.php?coupon_id=<?php echo $r['id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delete_product_coupon.php?coupon_id=<?php echo $r['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="btn btn-success float-end" href="add_product_coupon.php">Add coupon to product </a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>