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
include('../includes/header.php');
include('../includes/sidebar.php');

?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Coupons</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Coupon Code</th>
                        <th>Desription</th>
                        <th>Discount value</th>
                        <th>Remaining usage</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM coupons";
                    $res = mysqli_query($conn, $sql);
                    while ($r = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $r['id']; ?></th>
                            <td><?php echo $r['code']; ?></td>
                            <td><?php echo $r['coupon_desription']; ?></td>
                            <td><?php echo $r['discount_value']; ?></td>
                            <td><?php echo $r['max_usage'] - $r['times_used']; ?></td>
                            <td><?php echo $r['coupon_start_date']; ?></td>
                            <td><?php echo $r['coupon_end_date']; ?></td>

                            <td><a class="btn btn-primary" href="editcoupon.php?id=<?php echo $r['id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delcoupon.php?id=<?php echo $r['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="btn btn-success float-end" href="add_coupon.php">Add new
                coupon</a>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>