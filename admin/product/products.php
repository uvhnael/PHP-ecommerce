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

include("../includes/header.php");
include("../includes/sidebar.php");
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Product list</h2>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Published</th>
                        <th scope="col">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) { ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php
                                $sql = "SELECT * FROM `galleries` WHERE product_id={$product['id']} AND thumbnail=1";
                                $result = mysqli_query($conn, $sql);
                                $img = mysqli_fetch_assoc($result);
                                ?>
                                <img src="../<?php echo $img['image_path']; ?>" width="75px" height="75px">
                            <td><?php echo $product['product_name']; ?></td>
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
                            <td><?php echo $r['category_name']; ?></td>
                            <td><?php echo $product['regular_price']; ?></td>
                            <td><?php echo $product['quantity']; ?></td>
                            <td><?php if ($product['published'] == 1) echo "Yes";
                                else echo "No"; ?></td>
                            <td>
                                <a class="btn btn-primary" href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="btn btn-success float-end" href="add_product.php">Add new product</a>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>