<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
if (isset($_GET) & !empty($_GET)) {
    $id = $_GET['product_id'];
    $product_order = explode(",", $id);
} else {
    header("Location: cart.php");
}

include("includes/header.php");

$customer_id = $_COOKIE['user'];

if (isset($_GET['address_id'])) {
    $sql = "SELECT * FROM customers_addresses WHERE customer_id='$customer_id' AND id=" . $_GET['address_id'] . "";
    $result = mysqli_query($conn, $sql);
    $customer_address = mysqli_fetch_assoc($result);
} else {

    $sql = "SELECT * FROM customers_addresses WHERE customer_id='$customer_id' AND is_default=1";
    $result = mysqli_query($conn, $sql);
    $customer_address = mysqli_fetch_assoc($result);
}

$customer_address_id = $customer_address['id'];

// no address
if (empty($customer_address)) {
    header("Location: address.php");
}
?>

<div class="container section-padding">
    <div class="row">
        <div class="col-md-12">
            <h3>Customer Address</h3>
            <?php
            echo $customer_address['address_line1'] . ", " . $customer_address['address_line2'] . ", " . $customer_address['ward'] . ", " . $customer_address['district'] . ", " . $customer_address['city'];
            ?>
            <a href="select_address.php?product_id=<?php echo $id; ?>" class="btn"> Change Address
            </a>
        </div>
    </div>
</div>



<div class="container container-padding">
    <div class="row">
        <div class="col-md-12">
            <h3>Cart</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Variant</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Subtotal</th>
                    </tr>





                <tbody>
                    <?php
                    $sql = "SELECT * FROM carts WHERE customer_id='$customer_id'";
                    $result = mysqli_query($conn, $sql);
                    $carts = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $item_count = 0;
                    $items = 0;
                    $total = 0;
                    $product_list = [];
                    $quantity_list = [];
                    $price_list = [];
                    $vav_list = [];
                    foreach ($carts as $cart) {
                        $item_count++;
                        if (!in_array($item_count, $product_order)) {
                            continue;
                        }

                        $product_id = $cart['product_id'];

                        $sql = "SELECT * FROM products WHERE id='$product_id'";
                        $result = mysqli_query($conn, $sql);
                        $product = mysqli_fetch_assoc($result);

                        $subtotal = $product['regular_price'] * $cart['quantity'];
                        $total += $subtotal;

                        $sql = "SELECT * FROM galleries WHERE product_id='$product_id' AND thumbnail=1";
                        $result = mysqli_query($conn, $sql);
                        $product_images = mysqli_fetch_assoc($result);
                    ?>
                    <tr>

                        <td>
                            <img src="<?php echo $product_images['image_path']; ?>"
                                alt="<?php echo $product['product_name']; ?>" style="width: 100px;">
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
                        <td><a id="quantity_<?php echo $items;  ?>"
                                value="<?php echo $cart['quantity']; ?>"><?php echo $cart['quantity']; ?></a></td>
                        <td>
                            <?php
                                $sql = "SELECT * FROM variants INNER JOIN variant_values WHERE variant_attribute_value_id= " . $cart['variant_attribute_value_id'] . " AND variant_values.variant_id=variants.id";
                                $res = mysqli_query($conn, $sql);
                                $variant_value = mysqli_fetch_assoc($res);
                                ?>
                            <a id="variant_price_<?php echo $items; ?>"
                                value="<?php echo $variant_value['price']; ?>"><?php echo "₫" . $variant_value['price']; ?></a>
                            <?php
                                $subtotal = $variant_value['price'] * $cart['quantity'];
                                $total += $subtotal;
                                ?>
                        </td>
                        <td><a id="subtotal_<?php echo $items;  ?>"
                                value="<?php echo $subtotal; ?>"><?php echo "₫" . $subtotal; ?></a></td>

                    </tr>
                    <tr>
                        <td>
                            Product Coupon
                        </td>
                        <td colspan="4">
                            <?php
                                $sql = "SELECT * FROM coupons INNER JOIN product_coupons WHERE product_id=" . $product_id . " AND coupon_id=coupons.id AND coupons.times_used < coupons.max_usage AND coupons.coupon_start_date < NOW() AND coupons.coupon_end_date > NOW()";
                                $result = mysqli_query($conn, $sql);
                                $coupons = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                if (empty($coupons)) {
                                    echo "No Coupon";
                                } else {
                                ?>
                            <select class="form-control" id="<?php echo $items; ?>" name="product_coupon"
                                onchange="update_product_price(this)">
                                <option value="1">No Coupon</option>
                                <?php
                                        foreach ($coupons as $coupon) {
                                        ?>
                                <option value="<?php echo $coupon['id']; ?>"><?php echo $coupon['coupon_desription']; ?>
                                </option>
                                <?php } ?>
                            </select>
                            <?php } ?>

                        </td>
                        <td></td>

                    </tr>


                    <?php
                        $items++;

                        array_push($product_list, $product_id);
                        array_push($quantity_list, $cart['quantity']);
                        array_push($vav_list, $cart['variant_attribute_value_id']);
                    } ?>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td>Order coupon</td>
                        </td>
                        <td colspan="4">
                            <?php
                            $sql = "SELECT * FROM coupons WHERE discount_type = 'percentage' OR discount_type = 'fixed' AND times_used < max_usage AND coupon_start_date < NOW() AND coupon_end_date > NOW()";
                            $result = mysqli_query($conn, $sql);
                            $coupons = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            if (empty($coupons)) {
                                echo "No Coupon";
                            } else {
                            ?>
                            <select class="form-control" id="order_coupon" name="order_coupon"
                                onchange="update_total_price(this)">
                                <option value="1">No Coupon</option>
                                <?php
                                    foreach ($coupons as $coupon) {
                                    ?>
                                <option value="<?php echo $coupon['id']; ?>"><?php echo $coupon['coupon_desription']; ?>
                                </option>
                                <?php } ?>
                            </select>
                            <?php } ?>

                        </td>
                        <td><strong id="total"
                                value="<?php echo $total; ?>">₫<?php if ($total != 0) echo $total; ?></strong>
                        </td>
                    </tr>
                </tbody>

            </table>


        </div>
    </div>
</div>
<div class="container container-padding">

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
</div>

<div class="container container-padding">
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-end" onclick="createOrder()">Create Order</button>
        </div>
    </div>
</div>



<script>
// $product coupons add event listener

function update_total_price(coupon_id) {
    var coupon_id = coupon_id.id;

    // get option value
    var coupon_id = document.getElementById(coupon_id).value;

    var total = document.getElementById("total").getAttribute("value");

    if (coupon_id == '0') {
        document.getElementById("total").innerHTML = "₫" + total;
        return;
    }

    $.ajax({
        url: "update_product_price.php",
        type: "POST",
        data: {
            coupon_id: coupon_id,
            variant_price: total
        },
        success: function(data) {
            var data = JSON.parse(data);
            var new_price = data.product_price;
            document.getElementById("total").innerHTML = "₫" + new_price;
        }
    });
}

function update_price(product_id, new_price) {

    var quantity = document.getElementById("quantity_" + product_id).getAttribute("value");
    var subtotal = document.getElementById("subtotal_" + product_id);
    subtotal.innerHTML = "₫" + new_price * quantity;
    subtotal.setAttribute("value", new_price * quantity);

    // update total

    var total = 0;
    for (var i = 0; i < <?php echo $items; ?>; i++) {
        var subtotal = document.getElementById("subtotal_" + i).getAttribute("value");
        console.log(subtotal);
        total += parseFloat(subtotal);
    }
    console.log(total);

    document.getElementById("total").innerHTML = "₫" + total;
    document.getElementById("total").setAttribute("value", total);

    update_total_price(document.getElementById("order_coupon"));
}

function update_product_price(product_id) {

    var product_id = product_id.id;

    // get option value
    var coupon_id = document.getElementById(product_id).value;

    if (coupon_id == '0') {
        var variant_price = document.getElementById("variant_price_" + product_id);
        variant_price.innerHTML = "₫" + variant_price.getAttribute("value");

        update_price(product_id, variant_price.getAttribute("value"));
        return;
    }
    //get variant price
    var variant_price = document.getElementById("variant_price_" + product_id);

    variant_price = variant_price.getAttribute("value");

    $.ajax({
        url: "update_product_price.php",
        type: "POST",
        data: {
            coupon_id: coupon_id,
            variant_price: variant_price
        },
        success: function(data) {
            var data = JSON.parse(data);
            var new_price = data.product_price;
            var variant_price = document.getElementById("variant_price_" + product_id);
            variant_price.innerHTML = "₫" + new_price;

            update_price(product_id, new_price);
        }
    });



}


fetch('https://api.vietqr.io/v2/banks')
    .then(response => response.json())
    .then(data => {
        var banks = data.data;
        var bank_names = [];
        for (var i = 0; i < banks.length; i++) {
            bank_names.push(banks[i].name);
        }

        // put bank names and bank image into datalist
        var select = document.getElementById("payment_method");
        for (var i = 0; i < bank_names.length; i++) {
            var option = document.createElement("option");
            option.text = bank_names[i];
            option.value = bank_names[i];
            select.add(option);
        }



    });

function createOrder() {
    var product_list = <?php echo json_encode($product_list); ?>;
    var quantity_list = <?php echo json_encode($quantity_list); ?>;

    var vav_list = <?php echo json_encode($vav_list); ?>;
    var customer_address_id = <?php echo $customer_address_id; ?>;
    var coupon_id = document.getElementById("order_coupon");
    coupon_id = coupon_id.value;
    var coupons_list = [];
    var price_list = [];

    for (var i = 0; i < <?php echo $items; ?>; i++) {
        var subtotal = document.getElementById("subtotal_" + i).getAttribute("value");
        price_list.push(subtotal);
        var product_coupon_id = document.getElementById(i).value;
        coupons_list.push(product_coupon_id);
    }
    window.location.href = "create_order.php?product_list=" + product_list + "&quantity_list=" + quantity_list +
        "&price_list=" + price_list + "&vav_list=" + vav_list + "&customer_address_id=" + customer_address_id +
        "&coupons_list=" + coupons_list + "&coupon_id=" + coupon_id;
}
</script>

<?php

include("includes/footer.php");
?>