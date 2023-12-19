<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");

$customer_id = $_COOKIE['user'];

if (isset($_GET) & !empty($_GET)) {
    $order_id = $_GET['order_id'];
} else {
}
include("includes/header.php");
include("includes/navbar.php");

$sql = "SELECT * FROM order_items WHERE order_id='$order_id'";
$result = mysqli_query($conn, $sql);
$order_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Order Details</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($order_items as $order_item) {
                        $product_id = $order_item['product_id'];
                        $sql = "SELECT * FROM products WHERE id='$product_id'";
                        $result = mysqli_query($conn, $sql);
                        $product = mysqli_fetch_assoc($result);
                        $total += $order_item['price'];
                    ?>
                        <tr>
                            <td>
                                <a href="product.php?id=<?php echo $product['id']; ?>">

                                    <?php
                                    $sql = "SELECT * FROM galleries WHERE product_id='$product_id' AND thumbnail=1";
                                    $result = mysqli_query($conn, $sql);
                                    $product_images = mysqli_fetch_assoc($result);
                                    ?>
                                    <img src="<?php echo $product_images['image_path']; ?>" alt="" width="100px">
                                </a>
                            </td>
                            <td>
                                <?php echo $product['product_name']; ?>
                            </td>
                            <td>
                                <?php echo $order_item['quantity']; ?>
                            </td>
                            <td>
                                <?php echo $order_item['price']; ?>
                            </td>

                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td colspan="2"><b>Total</b></td>
                        <td><b><?php echo "₫" . $total; ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>





    <?php
    include("includes/footer.php");
    ?>