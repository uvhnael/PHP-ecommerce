<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
$sql = "SELECT * FROM carts WHERE customer_id=" . $_COOKIE['user'];
$result = mysqli_query($conn, $sql);
$carts = mysqli_fetch_all($result, MYSQLI_ASSOC);

include("includes/header.php");
include("includes/navbar.php");



?>
<div class="container section-padding">
    <div class="row mb-5">
        <div class="col-md-12">
            <h3>Cart</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="check-all" onclick="checkAll()">
                            </div>
                        </th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $count = 0;
                    foreach ($carts as $cart) {
                        $sql = "SELECT * FROM products WHERE id=" . $cart['product_id'];
                        $result = mysqli_query($conn, $sql);
                        $product = mysqli_fetch_assoc($result);

                        $count++;

                    ?>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="check-<?php echo $count; ?>" onclick="checkBox()">
                                </div>
                            </td>
                            <td>
                                <?php
                                $product_id = $product['id'];
                                $sql = "SELECT * FROM galleries WHERE product_id='$product_id' AND thumbnail=1";
                                $result = mysqli_query($conn, $sql);
                                $product_images = mysqli_fetch_assoc($result);
                                ?>
                                <img src="<?php echo $product_images['image_path']; ?>" alt="<?php echo $product['product_name']; ?>" style="width: 100px;">
                            </td>
                            <td><?php echo $product['product_name']; ?></td>

                            <td>
                                <?php

                                $sql = "SELECT * FROM variant_attribute_values WHERE variant_attribute_value_id=" . $cart['variant_attribute_value_id'];
                                $result = mysqli_query($conn, $sql);
                                $variant_attribute_values = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                foreach ($variant_attribute_values as $variant_attribute_value) {
                                    $attribute_value_id = $variant_attribute_value['attribute_value_id'];
                                    $sql = "SELECT * FROM attribute_values WHERE id=" . $attribute_value_id;
                                    $result = mysqli_query($conn, $sql);
                                    $attribute_value = mysqli_fetch_assoc($result);
                                    echo $attribute_value['attribute_value'] . ", ";
                                }
                                ?>
                            </td>
                            <td><?php echo $cart['quantity']; ?></td>
                            <td>
                                <?php
                                $sql = "SELECT * FROM variants INNER JOIN variant_values WHERE variant_attribute_value_id= " . $cart['variant_attribute_value_id'] . " AND variant_values.variant_id=variants.id";
                                $res = mysqli_query($conn, $sql);
                                $variant_value = mysqli_fetch_assoc($res);
                                echo "₫" . $variant_value['price'];
                                $subtotal = $variant_value['price'] * $cart['quantity'];
                                $total += $subtotal;
                                ?>
                            </td>
                            <td><?php echo "₫" . $subtotal; ?></td>
                            <td>
                                <a href="remove_form_cart.php?product_id=<?php echo $product_id; ?>" class="text-decoration-none fw-bold a-black">X</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6"></td>
                        <td><strong><?php if ($total != 0) echo "₫" . $total; ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>

            </table>
            <button type="submit" name="checkout" class="btn btn-primary float-end" onclick="checkOut()">Checkout</button>
        </div>
    </div>


</div>
<script>
    function checkAll() {
        var checkAll = document.getElementById("check-all");
        for (var i = 1; i <= <?php echo $count; ?>; i++) {
            var check = document.getElementById("check-" + i);
            if (checkAll.checked == true) {
                check.checked = true;
            } else {
                check.checked = false;
            }
        }
    }

    function checkBox() {
        var checkAll = document.getElementById("check-all");
        var count = 0;
        for (var i = 1; i <= <?php echo $count; ?>; i++) {
            var check = document.getElementById("check-" + i);
            if (check.checked == true) {
                count++;
            }
        }
        if (count == <?php echo $count; ?>) {
            checkAll.checked = true;
        } else {
            checkAll.checked = false;
        }
    }

    function checkOut() {
        var checkAll = document.getElementById("check-all");
        var count = 0;
        var product_id = [];
        for (var i = 1; i <= <?php echo $count; ?>; i++) {
            var check = document.getElementById("check-" + i);
            if (check.checked == true) {
                count++;
                product_id.push(i);
            }
        }
        if (count == 0) {
            alert("Please choose at least one product");
        } else {
            window.location.href = "checkout.php?product_id=" + product_id;
        }
    }
</script>
<?php
include("includes/footer.php");
?>