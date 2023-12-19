<?php
include("includes/db.php");
if (isset($_POST) & !empty($_POST)) {

    $coupon_id = $_POST['coupon_id'];
    $variant_price = $_POST['variant_price'];

    $sql = "SELECT * FROM `coupons` WHERE `id`='$coupon_id'";
    $res = mysqli_query($conn, $sql);
    $r = mysqli_fetch_assoc($res);

    if ($r['discount_type'] == 'product_percentage' || $r['discount_type'] == 'percentage') {
        $product_price = $variant_price - ($variant_price * $r['discount_value'] / 100);
    } elseif ($r['discount_type'] == 'product_fixed' || $r['discount_type'] == 'fixed') {
        $product_price = $variant_price - $r['discount_value'];
    }

    $product_price = max(0, $product_price);
    echo json_encode(['product_price' => $product_price]);
}
