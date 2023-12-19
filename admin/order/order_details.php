<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
}
if (isset($_GET) & !empty($_GET)) {
    $order_id = $_GET['id'];
} else {
    header('location: orders.php');
}

$sql = "SELECT * FROM order_items WHERE order_id='$order_id'";
$result = mysqli_query($conn, $sql);
$orderitems = mysqli_fetch_all($result, MYSQLI_ASSOC);

include("../includes/header.php");
include("../includes/sidebar.php");
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Order ID: <?php echo $order_id ?></h2>
            <h4>Status:
                <?php
                $sql = "SELECT order_statuses.id, order_statuses.status_name FROM order_statuses INNER JOIN orders ON orders.order_status_id = order_statuses.id WHERE orders.id='$order_id'";
                $result = mysqli_query($conn, $sql);
                $orderstatus = mysqli_fetch_assoc($result);
                echo $orderstatus['status_name'];
                echo " - ";
                echo $orderstatus['id'];
                ?>
                <?php if (isset($fmsg)) {
                    echo $fmsg;
                } ?>
            </h4>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Product Image</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>

                    </tr>

                <tbody>
                    <?php foreach ($orderitems as $orderitem) { ?>
                    <tr>
                        <td>
                            <?php
                                $sql = "SELECT * FROM `galleries` WHERE product_id={$orderitem['product_id']} AND thumbnail=1";
                                $result = mysqli_query($conn, $sql);
                                $product = mysqli_fetch_assoc($result);
                                ?>
                            <img src="../<?php echo $product['image_path']; ?>" width="75px" height="75px">
                        </td>
                        <td>
                            <?php
                                $sql = "SELECT * FROM products WHERE id={$orderitem['product_id']}";
                                $result = mysqli_query($conn, $sql);
                                $product = mysqli_fetch_assoc($result);
                                echo $product['product_name'];
                                ?>
                        </td>

                        <td><?php echo $orderitem['quantity']; ?></td>
                        <td><?php echo "₫" . $orderitem['price']; ?></td>

                    </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total</b></td>
                        <td><b>₫
                                <?php
                                $sql = "SELECT SUM(quantity * price) AS total_price FROM order_items WHERE order_id='$order_id'";
                                $result = mysqli_query($conn, $sql);
                                $order_total_price = mysqli_fetch_assoc($result);
                                echo $order_total_price['total_price'];
                                ?>
                            </b></td>
                    </tr>

                </tbody>



            </table>


            <?php if ($orderstatus['id'] == 1) { ?>
            <td><a class="btn btn-success float-end"
                    href="order_status.php?id=<?php echo $order_id; ?>&status=2">Accept</a></td>
            <?php } else if ($orderstatus['id'] == 2) { ?>
            <td><a class="btn btn-danger float-end"
                    href="order_status.php?id=<?php echo $order_id; ?>&status=4">Cancel</a></td>
            <?php } ?>
        </div>
    </div>
</div>


<?php
include("../includes/footer.php");
?>