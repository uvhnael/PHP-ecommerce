<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");

$customer_id = $_COOKIE['user'];

if (isset($_GET['status']) & !empty($_GET['status'])) {
    $status = $_GET['status'];
    $sql = "SELECT * FROM orders WHERE order_status_id='$status' AND customer_id='$customer_id' ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM orders WHERE customer_id='$customer_id' ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
include("includes/header.php");
include("includes/navbar.php");


?>




<div class="container section-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Your orders</h2>
            <div>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="order.php" class="btn btn-secondary">All Orders</a>
                    <a href="order.php?status=1" class="btn btn-secondary">Pending</a>
                    <a href="order.php?status=2" class="btn btn-secondary">Dispatch</a>
                    <a href="order.php?status=3" class="btn btn-secondary">Completed</a>
                    <a href="order.php?status=4" class="btn btn-secondary">Cancelled</a>
                </div>
                <?php if (count($orders) == 0) : ?>
                <h3>You have no orders</h3>
                <?php else : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Image</th>
                            <th>Order Date</th>
                            <th>Order Status</th>
                            <th>Total</th>
                            <th></th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td>
                                <?php
                                        $order_id = $order['id'];
                                        $sql = "SELECT * FROM order_items WHERE order_id='$order_id' LIMIT 1";
                                        $result = mysqli_query($conn, $sql);
                                        $order_item = mysqli_fetch_assoc($result);
                                        $product_id = $order_item['product_id'];
                                        $sql = "SELECT galleries.image_path FROM products INNER JOIN galleries WHERE products.id='$product_id' AND galleries.thumbnail=1 AND galleries.product_id=products.id";
                                        $result = mysqli_query($conn, $sql);
                                        $img = mysqli_fetch_assoc($result);
                                        ?>
                                <img src="<?php echo $img['image_path']; ?>" width="100px" />
                            <td><?php echo $order['created_at']; ?></td>
                            <td>
                                <?php
                                        $sql = "SELECT * FROM order_statuses WHERE id={$order['order_status_id']}";
                                        $result = mysqli_query($conn, $sql);
                                        $order_status = mysqli_fetch_assoc($result);
                                        ?>
                                <a style="color: <?php echo $order_status['color'] ?>"><i
                                        class="fa-solid fa-circle fa-xs"></i>
                                    <?php echo $order_status['status_name'] ?></a>
                            </td>
                            <td>
                                <?php
                                        $order_id = $order['id'];
                                        $sql = "SELECT * FROM order_items WHERE order_id='$order_id' ";
                                        $result = mysqli_query($conn, $sql);
                                        $order_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                        $total = 0;
                                        foreach ($order_items as $order_item) {
                                            $total += $order_item['price'] * $order_item['quantity'];
                                        }
                                        echo "<b>₫" . $total . "</b>";
                                        ?>
                            </td>
                            <td>
                                <a class="btn btn-dark"
                                    href="order_detail.php?order_id=<?php echo $order['id']; ?>">Detail</a>
                            </td>
                            <td>
                                <?php if ($order['order_status_id'] == 1) : ?>
                                <a class="btn btn-danger"
                                    href="cancel_order.php?order_id=<?php echo $order['id']; ?>">Cancel</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php
include("includes/footer.php");
?>