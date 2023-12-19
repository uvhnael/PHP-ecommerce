<?php
include 'includes/db.php';

if (isset($_POST) & !empty($_POST)) {
    $product_id = $_POST['product_id'];
    $attribute_value_id = $_POST['attribute_value_id'];

    $sql = "SELECT * FROM product_attributes WHERE product_id='$product_id'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($res);

    $count = count($attribute_value_id);
    $attribute_value_id = implode(', ', $attribute_value_id);

    if ($row == $count) {


        $sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id IN ($attribute_value_id) GROUP BY variant_attribute_value_id HAVING COUNT(variant_attribute_value_id) = '$count'";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($res);
        $r = mysqli_fetch_assoc($res);
        $variant_attribute_value_id = $r['variant_attribute_value_id'];
        $sql = "SELECT * FROM variants WHERE variant_attribute_value_id='$variant_attribute_value_id'";
        $res = mysqli_query($conn, $sql);
        $r = mysqli_fetch_assoc($res);
        $id = $r['id'];
        $sql = "SELECT * FROM variant_values WHERE variant_id='$id'";
        $res = mysqli_query($conn, $sql);
        $r = mysqli_fetch_assoc($res);

        $able_value_id = [];


        $sql = "SELECT DISTINCT vav2.attribute_value_id
                FROM variant_attribute_values vav1
                JOIN variant_attribute_values vav2 ON vav1.variant_attribute_value_id = vav2.variant_attribute_value_id
                WHERE vav1.attribute_value_id IN ($attribute_value_id)";
        $res = mysqli_query($conn, $sql);

        while ($av_id = mysqli_fetch_assoc($res)) {
            $able_value_id[] = $av_id['attribute_value_id'];
        }

        $attribute_value_id = explode(', ', $attribute_value_id);

        $tmp = [];

        for ($i = 0; $i < count($attribute_value_id); $i++) {
            for ($j = 0; $j < count($able_value_id); $j++) {
                if ($attribute_value_id[$i] == $able_value_id[$j])
                    continue;
                $sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id='$attribute_value_id[$i]' AND variant_attribute_value_id IN (SELECT variant_attribute_value_id FROM variant_attribute_values WHERE attribute_value_id='$able_value_id[$j]')";
                $res = mysqli_query($conn, $sql);

                if (mysqli_num_rows($res) > 0) {

                    $vav_id = mysqli_fetch_assoc($res)['variant_attribute_value_id'];

                    $sql = "SELECT * FROM variants INNER JOIN variant_values ON variants.id=variant_values.variant_id WHERE variant_attribute_value_id='$vav_id'";
                    $res = mysqli_query($conn, $sql);
                    $variant = mysqli_fetch_assoc($res);
                    if ($variant['quantity'] != 0) {
                        // delete $tmp at $j
                        array_push($tmp, $able_value_id[$j]);
                    }
                }
            }
        }
        $able_value_id = $tmp;
        echo json_encode(['able_value_id' => $able_value_id, 'tmp' => $tmp, 'attribute_value_id' => $attribute_value_id, 'price' => $r['price'], 'quantity' => $r['quantity']]);
    } else {
        $able_value_id = [];

        $sql = "SELECT DISTINCT vav2.attribute_value_id
            FROM variant_attribute_values vav1
            JOIN variant_attribute_values vav2 ON vav1.variant_attribute_value_id = vav2.variant_attribute_value_id
            WHERE vav1.attribute_value_id IN ($attribute_value_id)";
        $res = mysqli_query($conn, $sql);

        while ($r = mysqli_fetch_assoc($res)) {
            $able_value_id[] = $r['attribute_value_id'];
        }
        $tmp = [];

        $attribute_value_id = explode(', ', $attribute_value_id);

        for ($i = 0; $i < count($attribute_value_id); $i++) {
            for ($j = 0; $j < count($able_value_id); $j++) {
                if ($attribute_value_id[$i] == $able_value_id[$j])
                    continue;
                $sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id='$attribute_value_id[$i]' AND variant_attribute_value_id IN (SELECT variant_attribute_value_id FROM variant_attribute_values WHERE attribute_value_id='$able_value_id[$j]')";
                $res = mysqli_query($conn, $sql);



                $vav_id = mysqli_fetch_assoc($res)['variant_attribute_value_id'];

                $sql = "SELECT * FROM variants INNER JOIN variant_values ON variants.id=variant_values.variant_id WHERE variant_attribute_value_id='$vav_id'";
                $res = mysqli_query($conn, $sql);
                $variant = mysqli_fetch_assoc($res);
                if ($variant['quantity'] != 0) {
                    // delete $tmp at $j
                    array_push($tmp, $able_value_id[$j]);
                }
            }
        }
        $able_value_id = $tmp;
        echo json_encode(['able_value_id' => $able_value_id, 'price' => 0, 'quantity' => 0]);
    }
}
