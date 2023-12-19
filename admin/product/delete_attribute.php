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
function update_product($product_id, $conn)
{
    $sql = "SELECT * FROM variants INNER JOIN variant_values WHERE variants.id = variant_values.variant_id AND variants.product_id='$product_id'";
    $result = mysqli_query($conn, $sql);
    $variants = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $row = mysqli_num_rows($result);
    if ($row > 0) {
        $regular_price = 0;
        $quantity = 0;
        foreach ($variants as $variant) {
            $regular_price += $variant['price'];
            $quantity += $variant['quantity'];
        }
    } else {
        $regular_price = 0;
        $quantity = 0;
    }

    $updated_at = date("Y-m-d H:i:s");
    $updated_by = $_SESSION['id'];

    $sql = "UPDATE products SET regular_price='$regular_price', quantity='$quantity', updated_at='$updated_at', updated_by='$updated_by' WHERE id='$product_id'";
    $result = mysqli_query($conn, $sql);
}

if (isset($_GET) & !empty($_GET)) {
    $id = $_GET['id'];
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM attributes WHERE id='$id'";
    $res = mysqli_query($conn, $sql);
    $res = mysqli_fetch_assoc($res);
}

if (isset($_POST) & !empty($_POST)) {
    $id = $_POST['attribute_id'];
    $sql = "SELECT * FROM attribute_values WHERE attribute_id=$id";
    $res = mysqli_query($conn, $sql);
    while ($r = mysqli_fetch_assoc($res)) {
        $attribute_value_id = $r['id'];
        $sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id=$attribute_value_id";
        $res1 = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($res1);
        if ($row > 0) {
            $vav_id = mysqli_fetch_all($res1, MYSQLI_ASSOC);
            foreach ($vav_id as $vav) {
                $variant_attribute_value_id = $vav['variant_attribute_value_id'];
                $sql = "DELETE FROM variants WHERE variant_attribute_value_id=$variant_attribute_value_id";
                mysqli_query($conn, $sql);
            }
        }
    }

    $sql = "DELETE FROM attributes WHERE id=$id";
    $res = mysqli_query($conn, $sql);

    update_product($product_id, $conn);

    if ($res) {

        header('location: attributes.php?product_id=' . $product_id);
    } else {
        $fmsg = "Failed to delete attribute";
    }
}
include("../includes/header.php");
include("../includes/sidebar.php");
?>
<div class="container container-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h3>Delete attribute</h3>
            <form method="post">
                <div class="form-group">
                    <label for="attribute_id">Attribute name</label>
                    <input type="text" name="attribute_name" class="form-control" id="attribute_name" value="<?php echo $res['attribute_name']; ?>" placeholder="Attribute" readonly />
                </div>

                <input type="hidden" name="attribute_id" value="<?php echo $res['id'] ?>">

                </br>
                <div class="d-grid gap-2 col-4 mx-auto">
                    <button type="submit" class="btn btn-danger ">Delete</button>
                </div>


            </form>
        </div>
    </div>
</div>
<?php include("../includes/footer.php"); ?>