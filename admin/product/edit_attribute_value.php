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
    $id = $_GET['id'];
    $product_id = $_GET['product_id'];
    $sql = "SELECT attribute_values.id, attribute_values.attribute_value, attribute_values.color, attributes.attribute_name FROM attribute_values INNER JOIN attributes WHERE attribute_values.attribute_id = attributes.id AND attribute_values.id='$id' ";
    $res = mysqli_query($conn, $sql);
    $res = mysqli_fetch_assoc($res);
}
if (isset($_POST) & !empty($_POST)) {
    $id = $_POST['attribute_id'];
    $attribute_value = $_POST['attribute_value'];
    $attribute_name = $_POST['attribute_name'];
    if ($attribute_name == "Color") {
        $color = $_POST['color'];
    } else {
        $color = "NULL";
    }
    $sql = "UPDATE attribute_values SET attribute_value='$attribute_value', color='$color' WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        header('location: attributes.php?product_id=' . $product_id);
    } else {
        $fmsg = "Failed to update attribute";
    }
}

include("../includes/header.php");
include("../includes/sidebar.php");
?>
<div class="container container-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-6">
            <h3>Edit attribute values</h3>
            <form method="post">
                <div class="form-group">
                    <label for="attribute_id">Attribute name</label>
                    <input type="text" name="attribute_name" class="form-control" id="attribute_name" value="<?php echo $res['attribute_name']; ?>" placeholder="Attribute" readonly />
                </div>
                <div class="form-group">
                    <label for="attribute_value">Attribute value</label>
                    <input type="text" name="attribute_value" class="form-control" id="attribute_value" value="<?php echo $res['attribute_value']; ?>" placeholder="Attribute value" />
                </div>

                <?php if ($res['attribute_name'] == "Color") { ?>

                    <div class="form-group" id="color">
                        <label for="color">Color</label>
                        <input type="color" name="color" class="form-control" id="color" placeholder="Color" value="<?php echo $res['color'] ?>" />
                    </div>

                <?php } ?>
                <input type="hidden" name="attribute_id" value="<?php echo $res['id'] ?>">

                </br>
                <div class="d-grid gap-2 col-4 mx-auto">
                    <button type="submit" class="btn btn-primary ">Edit</button>
                </div>


            </form>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>