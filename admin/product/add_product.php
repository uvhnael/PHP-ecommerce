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
if (isset($_POST) & !empty($_POST)) {
    $productname = $_POST['product_name'];
    $productweight = $_POST['product_weight'];
    $published = $_POST['published'];
    $product_category = $_POST['product_category'];
    $created_by = $_SESSION['id'];

    $sql = "INSERT INTO products (product_name, product_weight, published, created_by) VALUES ('$productname',  '$productweight', '$published', '$created_by')";
    $result = mysqli_query($conn, $sql);
    $product_id = $conn->insert_id;

    if (!$result) {
        $fmsg = "Failed to Add Product";
    } else {

        $sql = "INSERT INTO product_categories (product_id, category_id) VALUES ('$product_id', '$product_category')";
        mysqli_query($conn, $sql);

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
                $thumbnail = 1;
                $display_order = 1;
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
                            $thumbnail = 0;
                        }

                        $file_uploaded = 0;
                    }
                }

                header("location:image_crop.php?product_id=$product_id");
            } else {

                // If no files selected
                echo "No files selected.";
            }
        }
    }
}
include '../includes/header.php';
include '../includes/sidebar.php';
?>


<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">


            <h2>Add Product</h2>


            <?php if (isset($fmsg)) {
                echo $fmsg;
            } ?>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" class="form-control" id="product_name">
                </div>

                <div class="form-group">
                    <label for="product_weight">Product Weight</label>
                    <input type="text" name="product_weight" class="form-control" id="product_weight">
                </div>
                <div class="form-group">
                    <label for="product_image">Product Image</label>
                    <input type="file" name="files[]" class="form-control" id="product_image" multiple>
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
                        $sql = "SELECT * FROM categories";
                        $res = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($res)) {
                        ?>
                            <option value="<?php echo $r['id']; ?>">
                                <?php echo $r['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                </br>

                <div class="d-grid gap-2 col-4 mx-auto">
                    <button type="submit" class="btn btn-primary ">Add product</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    <?php include '../includes/footer.php'; ?>