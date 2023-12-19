<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
if (isset($_GET) & !empty($_GET)) {
    $product_id = $_GET['product_id'];
    $quantity = $_GET['quantity'];
    $av_id = $_GET['av_id'];
    $av_id = explode(',', $av_id);
    $count = count($av_id);
    $av_id = implode(', ', $av_id);
} else {
    header("Location: index.php");
}

include("includes/header.php");

$customer_id = $_COOKIE['user'];

$sql = "SELECT * FROM customers_addresses WHERE customer_id='$customer_id'";
$result = mysqli_query($conn, $sql);
$customer_address = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM products WHERE id='$product_id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

$total = $product['regular_price'] * $quantity;

$sql = "SELECT * FROM galleries WHERE product_id='$product_id' AND thumbnail=1";
$result = mysqli_query($conn, $sql);
$product_images = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM attribute_values WHERE id IN ($av_id)";
$res = mysqli_query($conn, $sql);
$attribute_values = "";
while ($r = mysqli_fetch_assoc($res)) {
    $attribute_values .= $r['attribute_value'] . ", ";
}

$attribute_values = substr($attribute_values, 0, -2);

$sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id IN ($av_id) GROUP BY variant_attribute_value_id HAVING COUNT(variant_attribute_value_id) = '$count'";
$res = mysqli_query($conn, $sql);
$r = mysqli_fetch_assoc($res);
$variant_attribute_value_id = $r['variant_attribute_value_id'];

$sql = "SELECT * FROM variants INNER JOIN variant_values WHERE variant_attribute_value_id='$variant_attribute_value_id' AND variant_values.variant_id=variants.id";
$res = mysqli_query($conn, $sql);
$variant_value = mysqli_fetch_assoc($res);

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Your Address</h3>
            <a>
                <?php
                echo $customer_address['address_line1'] . ", " . $customer_address['address_line2'] . ", " . $customer_address['ward'] . ", " . $customer_address['district'] . ", " . $customer_address['city'];
                ?>
            </a>
        </div>
    </div>
</div>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>Cart</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>

                        <td>
                            <img src="<?php echo $product_images['image_path']; ?>" alt="<?php echo $product['product_name']; ?>" style="width: 100px;">
                        </td>
                        <td><?php echo $product['product_name']; ?></td>
                        <td>
                            <?php echo $attribute_values ?>
                        </td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo "₫" . $variant_value['price']; ?></td>
                        <td><?php echo "₫" . $total; ?></td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Payment</h3>
            <form method="post">
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method">
                        <option value="cash_on_delivery">Cash on Delivery</option>
                        <option value="momo">Momo</option>
                        <option value="zalopay">ZaloPay</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <button class="btn btn-primary float-end" onclick="createOrder()">Buy</button>


    <script>
        fetch('https://api.vietqr.io/v2/banks')
            .then(response => response.json())
            .then(data => {
                var banks = data.data;
                var bank_names = [];
                for (var i = 0; i < banks.length; i++) {
                    bank_names.push(banks[i].name);
                }
                var select = document.getElementById("payment_method");
                for (var i = 0; i < bank_names.length; i++) {
                    var option = document.createElement("option");
                    option.text = bank_names[i];
                    option.value = bank_names[i];
                    select.add(option);
                }
            });

        function createOrder() {
            var product_id = <?php echo $product_id; ?>;
            var quantity = <?php echo $quantity; ?>;
            var price = <?php echo $variant_value['price']; ?>;
            var vav_id = <?php echo $variant_attribute_value_id ?>;

            window.location.href = "create_order.php?product_list=" + product_id + "&quantity_list=" + quantity +
                "&price_list=" + price + "&vav_list=" + vav_id + "";
        }
    </script>

    <?php

    include("includes/footer.php");
    ?>