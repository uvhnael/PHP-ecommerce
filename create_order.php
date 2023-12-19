<?php
if (!isset($_COOKIE['user'])) {
    header("Location: login.php");
}
include("includes/db.php");
if (isset($_GET) & !empty($_GET)) {
    $product_list = $_GET['product_list'];
    $quantity_list = $_GET['quantity_list'];
    $price_list = $_GET['price_list'];
    $vav_list = $_GET['vav_list'];
    $customer_address_id = $_GET['customer_address_id'];
    $coupon_id = $_GET['coupon_id'];
    $coupons_list = $_GET['coupons_list'];

    $product_list = explode(",", $product_list);
    $quantity_list = explode(",", $quantity_list);
    $price_list = explode(",", $price_list);
    $vav_list = explode(",", $vav_list);
    $coupons_list = explode(",", $coupons_list);
    array_push($coupons_list, $coupon_id);
} else {
    header("Location: cart.php");
}


for ($i = 0; $i < count($coupons_list); $i++) {
    $coupon_id = $coupons_list[$i];
    $sql = "UPDATE coupons SET times_used = times_used + 1 WHERE id = '$coupon_id'";
    mysqli_query($conn, $sql);
}


$customer_id = $_COOKIE['user'];
// create order
$sql = "INSERT INTO orders (customer_id, coupon_id,  order_status_id, customer_address_id) VALUES ('$customer_id', '$coupon_id', '1', '$customer_address_id')";
mysqli_query($conn, $sql);

// get just inserted order id
$order_id = mysqli_insert_id($conn);


for ($i = 0; $i < count($product_list); $i++) {
    $product_id = $product_list[$i];
    $quantity = $quantity_list[$i];
    $price = $price_list[$i];
    $vav_id = $vav_list[$i];

    $sql = "INSERT INTO order_items (product_id, order_id, price, quantity, variant_attribute_value_id) VALUES ($product_id, '$order_id', '$price', '$quantity' , '$vav_id')";
    mysqli_query($conn, $sql);

    $sql = "DELETE FROM carts WHERE customer_id='$customer_id' and product_id='$product_id' AND quantity='$quantity' AND variant_attribute_value_id='$vav_id'";
    mysqli_query($conn, $sql);

    // update product quantity
    // variant_values
    $sql = "SELECT * FROM variants INNER JOIN variant_values ON variants.id=variant_values.variant_id WHERE variant_attribute_value_id='$vav_id'";
    $result = mysqli_query($conn, $sql);
    $variant = mysqli_fetch_assoc($result);
    $variant_id = $variant['variant_id'];
    $quantity = $variant['quantity'] - $quantity_list[$i];
    $sql = "UPDATE variant_values SET quantity='$quantity' WHERE variant_id='$variant_id'";
    mysqli_query($conn, $sql);

    // products
    $sql = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    $quantity = $product['quantity'] - $quantity_list[$i];

    $sql = "UPDATE products SET quantity='$quantity' WHERE id='$product_id'";
    mysqli_query($conn, $sql);
}

// send notification to admin

$sql = "SELECT staffs.* FROM staffs INNER JOIN staff_roles ON staffs.id = staff_roles.staff_id WHERE staffs.active = 1 AND staff_roles.role_id = 1";
$result = mysqli_query($conn, $sql);
$staff = mysqli_fetch_assoc($result);
$staff_id = $staff['id'];

$tittle = "Order #" . $order_id;
$customer_name = $_COOKIE['user_name'];
$content = "You have a new order form " . $customer_name . " with order id " . $order_id . ". Please check it out.";
$notification_expiry_date = date("Y-m-d H:i:s", strtotime("+3 day"));
// $notification_expiry_date = date("Y-m-d H:i:s", strtotime("+1 minute"));
$sql = "INSERT INTO `notifications`(`account_id`, `tittle`, `content`, `seen`, `noitification_expiry_date`) VALUES ('$staff_id', '$tittle', '$content', '0', '$notification_expiry_date')";
mysqli_query($conn, $sql);

header("Location: order.php");
