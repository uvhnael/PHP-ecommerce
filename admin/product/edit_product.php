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
    $product_id = $_GET['id'];
} else {
    header('location: products.php');
}

if (isset($_POST) & !empty($_POST)) {
    if (isset($_POST['product_name']) & !empty($_POST['product_name'])) {
        $product_name = $_POST['product_name'];

        $product_weight = $_POST['product_weight'];
        $published = $_POST['published'];
        $update_by = $_SESSION['id'];
        $updated_at = date("Y-m-d H:i:s");
        $sql = "UPDATE products SET product_name='$product_name', product_weight='$product_weight', published='$published', updated_at='$updated_at', updated_by='$update_by' WHERE id=$product_id";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            $product_category = $_POST['product_category'];
            $sql = "DELETE FROM product_categories WHERE product_id=$product_id";
            $result = mysqli_query($conn, $sql);

            $sql = "INSERT INTO product_categories (product_id, category_id) VALUES ('$product_id', '$product_category')";
            $result = mysqli_query($conn, $sql);

            // https://www.geeksforgeeks.org/how-to-select-and-upload-multiple-files-with-html-and-php-using-http-post/
            // multiple image upload process

            if (isset($_POST) & !empty($_POST)) {
                // Configure upload directory and allowed file types
                $upload_dir = '../../img/' . DIRECTORY_SEPARATOR;
                $allowed_types = array('jpg', 'png', 'jpeg', 'gif');

                // Define maxsize for files i.e 2MB
                $maxsize = 1 * 1024 * 1024;

                // Checks if user sent an empty form 
                if (!empty(array_filter($_FILES['files']['name']))) {

                    // Loop through each file in files[] array
                    $file_uploaded = 0;
                    $sql = "SELECT * FROM `galleries` WHERE product_id='$product_id'";
                    $result = mysqli_query($conn, $sql);
                    // count how many rows found
                    $count = mysqli_num_rows($result);
                    $display_order = $count + 1;
                    foreach ($_FILES['files']['tmp_name'] as $key => $value) {

                        $file_tmpname = $_FILES['files']['tmp_name'][$key];
                        $file_name = $_FILES['files']['name'][$key];
                        $file_size = $_FILES['files']['size'][$key];
                        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

                        // Set upload file path
                        $filepath = $upload_dir . $file_name;

                        // Check file type is allowed or not
                        if (in_array(strtolower($file_ext), $allowed_types)) {

                            // Verify file size - 2MB max 
                            if ($file_size > $maxsize)
                                echo "Error: File size is larger than the allowed limit.";

                            // If file with name already exist then append time in
                            // front of name of the file to avoid overwriting of file
                            if (file_exists($filepath)) {
                                $filepath = $upload_dir . time() . $file_name;

                                if (move_uploaded_file($file_tmpname, $filepath))
                                    $file_uploaded = 1;
                            } else 
                                if (move_uploaded_file($file_tmpname, $filepath))
                                $file_uploaded = 1;
                        }
                        if ($file_uploaded == 1) {
                            $sql = "INSERT INTO `galleries`(`product_id`, `image_path`, `thumbnail`, `display_order`, `created_by`, `updated_by`) VALUES ('$product_id', '$filepath', '$thumbnail', '$display_order', '$createdby', '$updatedby')";
                            $result = mysqli_query($conn, $sql);

                            if ($result) {
                                $display_order++;
                            }

                            $file_uploaded = 0;
                        }
                    }

                    header("location:image_crop.php?product_id=$product_id&goto=attributes");
                } else {
                    header("location:attributes.php?product_id=$product_id");
                }
            } else {
                $fmsg = "Failed to Update Data.";
            }
        }
    }
}

$sql = "SELECT * FROM products WHERE id='$product_id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/sidebar.php");

?>


<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Product</h2>
            <?php if (isset($fmsg)) {
                echo $fmsg;
            } ?>
            <div class="container container-padding">

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Published</th>

                        </tr>
                    </thead>

                    <tbody>

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

                        </tr>
                    </tbody>
                </table>
            </div>



            <div id="product">
                <h2>Edit Product</h2>

                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" name="product_name" class="form-control" id="product_name" value="<?php echo $product['product_name']; ?>" placeholder="Product Name">
                    </div>

                    <div class="form-group">
                        <label for="product_weight">Product Weight</label>
                        <input type="text" name="product_weight" class="form-control" id="product_weight" value="<?php echo $product['product_weight']; ?>" placeholder="Product Weight">
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
                                            <td scope="row"> <?php echo $gallery['id']; ?></td>
                                            <td><img src="../<?php echo $gallery['image_path']; ?>" width="75px" height="75px"></td>

                                            <td><?php if ($gallery['thumbnail'] == 0) { ?><a class="btn btn-secondary" href="set_thumbnail.php?product_id=<?php echo $product['id']; ?>&gallery_id=<?php echo $gallery['id'] ?>">Set
                                                        thumbnail</a>
                                                <?php } ?>
                                            </td>
                                            <td><a class="btn btn-danger" href="delete_image.php?product_id=<?php echo $product['id']; ?>&gallery_id=<?php echo $gallery['id'] ?>">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <input type="file" name="files[]" class="form-control" id="product_image" placeholder="Product Image" multiple>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="published">Published</label>
                        <select name="published" class="form-control" id="published">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_category">Product Category</label>
                        <select name="product_category" class="form-control" id="product_category">

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

                            <option value="<?php echo $r['id']; ?>"><?php echo $r['category_name']; ?></option>

                            <?php
                            $product_id = $product['id'];
                            $sql = "SELECT category_id FROM product_categories WHERE product_id='$product_id'";
                            $result = mysqli_query($conn, $sql);
                            $product_category = mysqli_fetch_assoc($result);
                            $product_category_id = $product_category['category_id'];

                            $sql = "SELECT * FROM categories WHERE id != '$product_category_id'";
                            $res = mysqli_query($conn, $sql);
                            while ($r = mysqli_fetch_assoc($res)) {
                            ?>
                                <option value="<?php echo $r['id']; ?>"><?php echo $r['category_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                    </br>

                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include("../includes/footer.php");
?>