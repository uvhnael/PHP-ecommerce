<?php
session_start();
include("../includes/db.php");
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 2) {
        header('location: ../index.php');
    }
}
if (isset($_GET['id']) & !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
} else {
    header('location: products.php');
}

if (isset($_POST) & !empty($_POST)) {
    $id = $_POST['id'];

    $sql = "DELETE FROM product_categories WHERE product_id='$id'";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM galleries WHERE product_id='$id'";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM product_attributes WHERE product_id='$id'";
    $result = mysqli_query($conn, $sql);

    $sql = "SELECT * FROM variants WHERE product_id='$id'";
    $result = mysqli_query($conn, $sql);
    $variants = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($variants as $variant) {
        $variant_id = $variant['id'];
        $sql = "DELETE FROM variant_values WHERE variant_id='$variant_id'";
        $result = mysqli_query($conn, $sql);
    }

    $sql = "DELETE FROM variants WHERE product_id='$id'";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM products WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
}


include("../includes/header.php");
include("../includes/sidebar.php");
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Delete Product</h2>
            <?php if (isset($fmsg)) {
                echo $fmsg;
            } ?>

            <form action="delete_product.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" class="form-control" id="product_name" value="<?php echo $product['product_name'] ?>" placeholder="Product Name" readonly>
                </div>
                <div class="form-group">
                    <label for="regular_price">Regular Price</label>
                    <input type="text" name="regular_price" class="form-control" id="regular_price" value="<?php echo $product['regular_price'] ?>" placeholder="Regular Price" readonly>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="text" name="quantity" class="form-control" id="quantity" value="<?php echo $product['quantity'] ?>" placeholder="Quantity" readonly>
                </div>
                <div class="form-group">
                    <label for="product_weight">Product Weight</label>
                    <input type="text" name="product_weight" class="form-control" id="product_weight" value="<?php echo $product['product_weight'] ?>" placeholder="Product Weight" readonly>
                </div>
                <div class="form-group">
                    <label for="product_image">Product Image</label>
                    <div>
                        <table class="table table-striped">
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM `galleries` WHERE product_id={$product['id']}";
                                $result = mysqli_query($conn, $sql);
                                $galleries = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                foreach ($galleries as $gallery) {
                                ?>
                                    <tr>
                                        <td scope="row"><?php echo $gallery['id']; ?></td>
                                        <td><?php echo $gallery['image_path']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label for="published">Published</label>
                    <input type="text" name="published" class="form-control" id="published" value="<?php if ($product['published'] == 1) echo "Yes";
                                                                                                    else echo "No"; ?>" placeholder="Product Weight" readonly>

                </div>
                <div class="form-group">
                    <label for="product_category">Product Category</label>
                    <?php
                    $product_id = $product['id'];
                    $sql = "SELECT category_id FROM product_categories WHERE product_id='$product_id'";
                    $result = mysqli_query($conn, $sql);
                    $product_category = mysqli_fetch_assoc($result);
                    $product_category_id = $product_category['category_id'];

                    $sql = "SELECT * FROM categories WHERE id = '$product_category_id'";
                    $res = mysqli_query($conn, $sql);
                    $r = mysqli_fetch_assoc($res);
                    ?>
                    <input type="text" name="product_category" class="form-control" id="product_category" value="<?php echo $r['category_name']; ?>" placeholder="Product Weight" readonly>
                </div>
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                </br>
                <button type="submit" class="btn btn-primary float-end">Submit</button>
            </form>
        </div>
    </div>
</div>


<?php include '../includes/footer.php'; ?>