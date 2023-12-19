<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
}

if (isset($_GET['status']) & !empty($_GET['status'])) {
    $status = $_GET['status'];
    $sql = "SELECT * FROM orders WHERE order_status_id='$status' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $sql = "SELECT * FROM orders ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
include("../includes/header.php");
include("../includes/sidebar.php");
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Orders list</h2>

            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="orders.php" class="btn btn-secondary">All Orders</a>
                <a href="orders.php?status=1" class="btn btn-secondary">Pending</a>
                <a href="orders.php?status=2" class="btn btn-secondary">Dispatch</a>
                <a href="orders.php?status=3" class="btn btn-secondary">Completed</a>
                <a href="orders.php?status=4" class="btn btn-secondary">Cancelled</a>
            </div>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Price</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td scope="row"><?php echo $order['id']; ?></td>
                            <td>
                                <?php
                                $sql = "SELECT * FROM customers WHERE id={$order['customer_id']}";
                                $result = mysqli_query($conn, $sql);
                                $customer = mysqli_fetch_assoc($result);
                                echo $customer['last_name'] . " " . $customer['first_name'];
                                ?>
                            </td>
                            <td><?php echo $order['created_at']; ?></td>
                            <?php
                            $sql = "SELECT SUM(quantity * price) AS total_price FROM order_items WHERE order_id={$order['id']}";
                            $result = mysqli_query($conn, $sql);
                            $order_total_price = mysqli_fetch_assoc($result);
                            ?>
                            <td>
                                <?php
                                $sql = "SELECT * FROM order_statuses WHERE id={$order['order_status_id']}";
                                $result = mysqli_query($conn, $sql);
                                $order_status = mysqli_fetch_assoc($result);
                                ?>
                                <a style="color: <?php echo $order_status['color'] ?>"><i class="fa-solid fa-circle fa-xs"></i> <?php echo $order_status['status_name'] ?></a>
                            </td>
                            <td><?php echo "₫" . $order_total_price['total_price']; ?></td>

                            <td><a class="btn btn-secondary" href="order_details.php?id=<?php echo $order['id']; ?>">Details</a></td>
                            <td>
                                <?php
                                if ($order['order_status_id'] == 1) {
                                ?>
                                    <a class="btn btn-success" href="order_status.php?id=<?php echo $order['id']; ?>&status=2">Accept</a>
                                <?php
                                } else if ($order['order_status_id'] == 2) {
                                ?>
                                    <a class="btn btn-danger" href='order_status.php?id=<?php $order['id']; ?>&status=4'>Cancel</a>

                                <?php
                                }
                                ?>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
?>