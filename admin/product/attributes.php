<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 2) {
        header('location: ../index.php');
    }
}

if (isset($_GET) & !empty($_GET)) {
    $product_id = $_GET['product_id'];
}
function update_product($product_id, $conn)
{
    $sql = "SELECT * FROM variants INNER JOIN variant_values WHERE variants.id = variant_values.variant_id AND variants.product_id='$product_id'";
    $result = mysqli_query($conn, $sql);
    $variants = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $regular_price = 0;
    $quantity = 0;
    foreach ($variants as $variant) {
        $regular_price += $variant['price'];
        $quantity += $variant['quantity'];
    }
    $regular_price = $regular_price / count($variants);

    $updated_at = date("Y-m-d H:i:s");
    $updated_by = $_SESSION['id'];

    $sql = "UPDATE products SET regular_price='$regular_price', quantity='$quantity', updated_at='$updated_at', updated_by='$updated_by' WHERE id='$product_id'";
    $result = mysqli_query($conn, $sql);
}




if (isset($_POST) & !empty($_POST)) {
    if (isset($_POST['attribute_value']) & !empty($_POST['attribute_value'])) {
        $attribute_value = filter_var($_POST['attribute_value'], FILTER_SANITIZE_STRING);
        $attribute_id = filter_var($_POST['attribute_id'], FILTER_SANITIZE_NUMBER_INT);
        $sql = "INSERT INTO attribute_values (attribute_id, attribute_value) VALUES ('$attribute_id', '$attribute_value')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $smsg = "Attribute value added";
        } else {
            $fmsg = "Failed to add attribute value";
        }
    } elseif (isset($_POST['attribute_name']) & !empty($_POST['attribute_name'])) {
        $attribute_name = filter_var($_POST['attribute_name'], FILTER_SANITIZE_STRING);
        $sql = "INSERT INTO attributes (attribute_name) VALUES ('$attribute_name')";
        $result = mysqli_query($conn, $sql);

        $product_id = $_GET['product_id'];
        $attribute_id = mysqli_insert_id($conn);
        $sql = "INSERT INTO product_attributes (product_id, attribute_id) VALUES ('$product_id', '$attribute_id')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $smsg = "Attribute added";
        } else {
            $fmsg = "Failed to add attribute";
        }
    }

    if (isset($_POST['price']) & !empty($_POST['price'])) {
        if (isset($_POST['quantity']) & !empty($_POST['quantity'])) {

            $count = 0;
            $attribute_value_id = "attribute_value_" . $count;
            $attribute_value = array();
            while (isset($_POST[$attribute_value_id]) & !empty($_POST[$attribute_value_id])) {
                $attribute_value_id = filter_var($_POST[$attribute_value_id], FILTER_SANITIZE_NUMBER_INT);
                // add to array
                $attribute_value[$count] = $attribute_value_id;
                $count++;
                $attribute_value_id = "attribute_value_" . $count;
            }

            $sql = "SELECT variant_attribute_value_id FROM variant_attribute_values WHERE attribute_value_id IN (" . implode(', ', $attribute_value) . ") GROUP BY variant_attribute_value_id HAVING COUNT(variant_attribute_value_id) = $count";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_num_rows($result);
            if ($row != 0) {
                $fmsg = "Variant already exists";
            } else {
                $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
                $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
                // find max variant_attribute_value_id
                $sql = "SELECT MAX(variant_attribute_value_id) AS max FROM variants";
                $result = mysqli_query($conn, $sql);
                $max = mysqli_fetch_assoc($result);
                $max = $max['max'];
                $max++;

                $sql = "INSERT INTO variants (product_id, variant_attribute_value_id) VALUES ('$product_id' , '$max')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $variant_id = mysqli_insert_id($conn);
                    $sql = "INSERT INTO variant_values (variant_id, price, quantity) VALUES ('$variant_id', '$price', '$quantity')";
                    $result = mysqli_query($conn, $sql);

                    update_product($product_id, $conn);

                    $count = 0;
                    $attribute_value_id = "attribute_value_" . $count;
                    while (isset($_POST[$attribute_value_id]) & !empty($_POST[$attribute_value_id])) {
                        $attribute_value_id = filter_var($_POST[$attribute_value_id], FILTER_SANITIZE_NUMBER_INT);
                        $sql = "INSERT INTO variant_attribute_values (variant_attribute_value_id, attribute_value_id) VALUES ('$max', '$attribute_value_id')";
                        $result = mysqli_query($conn, $sql);
                        $count++;
                        $attribute_value_id = "attribute_value_" . $count;
                    }
                } else {
                    $fmsg = "Failed to add variant";
                }
            }
        }
    }
}
include("../includes/header.php");
include("../includes/sidebar.php");
?>



<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between">
                <a href="edit_product.php?id=<?php echo $product_id; ?>" class="btn btn-primary float-start">Back</a>
                <a href="edit_product_tag.php?product_id=<?php echo $product_id; ?>" class="btn btn-primary float-end">Edit
                    Tags</a>
            </div>
            <h2>Attributes list</h2>
            <table class="table  table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Attribute Name</th>
                        <th></th>
                        <th>Attribute Value</th>
                        <th>Color</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM attributes INNER JOIN product_attributes ON attributes.id = product_attributes.attribute_id WHERE product_attributes.product_id=$product_id";
                    $res = mysqli_query($conn, $sql);
                    while ($r = mysqli_fetch_assoc($res)) {
                    ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="attribute_id" value="<?php echo $r['attribute_id']; ?>">


                            <tr>
                                <td><?php echo $r['id']; ?></td>
                                <td><?php echo $r['attribute_name']; ?></td>
                                <td colspan="3"></td>
                                <td>
                                    <a class="btn btn-primary" href="edit_attribute.php?product_id=<?php echo $product_id; ?>&id=<?php echo $r['id']; ?>">Edit</a>
                                    <a class="btn btn-danger" href="delete_attribute.php?product_id=<?php echo $product_id; ?>&id=<?php echo $r['id']; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                            $sql1 = "SELECT * FROM attribute_values WHERE attribute_id=" . $r['id'];
                            $res1 = mysqli_query($conn, $sql1);
                            while ($r1 = mysqli_fetch_assoc($res1)) {
                            ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $r1['id']; ?></td>
                                    <td><?php echo $r1['attribute_value']; ?></td>
                                    <td><?php echo $r1['color']; ?></td>
                                    <td>
                                        <a class="btn btn-primary" href="edit_attribute_value.php?product_id=<?php echo $product_id; ?>&id=<?php echo $r1['id']; ?>">Edit</a>
                                        <a class="btn btn-danger" href="delete_attribute_value.php?product_id=<?php echo $product_id; ?>&id=<?php echo $r1['id']; ?>">Delete</a>
                                    </td>
                                <?php
                            }
                                ?>
                                </tr>
                                <td colspan="3"></td>
                                <td>
                                    <input type="text" name="attribute_value" class="form-control" id="attribute_value" placeholder="Add new attribute value">
                                </td>

                                <td> <input type="submit" class="btn btn-primary" value="Add"></td>
                                <td></td>

                                <tr>
                        </form>


                    <?php
                    }
                    ?>
                    <tr>
                        <form method="post" enctype="multipart/form-data">
                            <td></td>
                            <td>
                                <input type="text" name="attribute_name" class="form-control" id="attribute_name" placeholder="Add new attribute name">
                            </td>
                            <td>
                                <input type="submit" class="btn btn-primary" value="Add">
                            </td>

                            <td colspan="3"></td>

                        </form>
                    </tr>
                </tbody>
            </table>
            <div>

                <h2>Add Variant</h2>
                <div class="row">

                    <?php
                    $sql = "SELECT * FROM attributes INNER JOIN product_attributes ON attributes.id=product_attributes.attribute_id WHERE product_attributes.product_id='$product_id'";
                    $result = mysqli_query($conn, $sql);
                    $attributes = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $attribute_count = count($attributes);
                    foreach ($attributes as $attribute) {
                    ?>
                        <div class="col-2">
                            <label><?php echo $attribute['attribute_name'] ?></label>
                        </div>

                    <?php } ?>
                    <div class="col-2">
                        <label>Price</label>
                    </div>
                    <div class="col-2">
                        <label>Quantity</label>
                    </div>
                </div>


                <?php
                $sql = "SELECT * FROM variants WHERE product_id='$product_id'";
                $result = mysqli_query($conn, $sql);
                $variants = mysqli_fetch_all($result, MYSQLI_ASSOC);

                foreach ($variants as $variant) {
                    $variant_attribute_value_id = $variant['variant_attribute_value_id'];
                    $sql = "SELECT * FROM variant_attribute_values WHERE variant_attribute_value_id='$variant_attribute_value_id'";
                    $result = mysqli_query($conn, $sql);
                    $variant_attribute_values = mysqli_fetch_all($result, MYSQLI_ASSOC);
                ?>
                    <div class="row pt-2">
                        <?php
                        $attribute_value_count = count($variant_attribute_values);
                        foreach ($variant_attribute_values as $variant_attribute_value) {
                            $attribute_value_id = $variant_attribute_value['attribute_value_id'];
                            $sql = "SELECT * FROM attribute_values WHERE id='$attribute_value_id'";
                            $result = mysqli_query($conn, $sql);
                            $attribute_value = mysqli_fetch_assoc($result);
                        ?>
                            <div class="col-2 form-group">
                                <input class="form-control" value="<?php echo $attribute_value['attribute_value'] ?>" readonly>
                            </div>
                        <?php }
                        for ($i = $attribute_value_count; $i < $attribute_count; $i++) { ?>
                            <div class="col-2 form-group">
                            </div>
                        <?php }

                        $variant_id = $variant['id'];
                        $sql = "SELECT * FROM variant_values WHERE variant_id='$variant_id'";
                        $result = mysqli_query($conn, $sql);
                        $variant_values = mysqli_fetch_assoc($result);
                        ?>
                        <div class="col-2 form-group">
                            <input class="form-control" value="<?php echo $variant_values['price'] ?>">
                        </div>
                        <div class="col-2 form-group">
                            <input class="form-control" value="<?php echo $variant_values['quantity'] ?>">
                        </div>
                        <div class="col-2 form-group">
                            <a class="btn btn-danger" href="delete_variant.php?id=<?php echo $variant['id']; ?>">Delete</a>
                        </div>
                    </div>
                <?php }
                ?>


                <form method="post" enctype="multipart/form-data">
                    <div class="row pt-2">
                        <?php
                        $sql = "SELECT * FROM attributes INNER JOIN product_attributes ON attributes.id=product_attributes.attribute_id WHERE product_attributes.product_id='$product_id'";

                        $result = mysqli_query($conn, $sql);
                        $attributes = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        $count = 0;
                        foreach ($attributes as $attribute) {
                            $attribute_id = $attribute['attribute_id'];
                            $sql = "SELECT * FROM attribute_values WHERE attribute_id='$attribute_id'";
                            $result = mysqli_query($conn, $sql);
                            $attribute_values = mysqli_fetch_all($result, MYSQLI_ASSOC);

                        ?>

                            <div class="col-2 form-group">
                                <select name="attribute_value_<?php echo $count; ?>" class="form-control">
                                    <?php foreach ($attribute_values as $attribute_value) { ?>
                                        <option value="<?php echo $attribute_value['id']; ?>">
                                            <?php echo $attribute_value['attribute_value']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php $count++;
                        } ?>
                        <div class="col-2 form-group">
                            <input type="text" name="price" class="form-control" id="price" placeholder="Price">
                        </div>
                        <div class="col-2 form-group">
                            <input type="text" name="quantity" class="form-control" id="quantity" placeholder="Quantity">
                        </div>

                        <div class="col-1 form-group float-end">
                            <input type="submit" class="btn btn-primary" value="Add">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include("../includes/footer.php");
?>