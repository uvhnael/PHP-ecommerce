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
} else {
    header('location: categories.php');
}

if (isset($_POST) & !empty($_POST)) {
    $category_name = $_POST['category_name'];
    $parent_id = $_POST['parent_id'];
    $active = $_POST['active'];
    $category_description = $_POST['category_description'];
    $update_by = $_SESSION['id'];
    $updated_at = date("Y-m-d H:i:s");
    $sql = "UPDATE categories SET category_description='$category_description',  category_name='$category_name', parent_id='$parent_id', active='$active',updated_at='$updated_at', updated_by='$update_by' WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        header('location: categories.php');
    } else {
        $fmsg = "Failed to Update Data.";
    }
}

$sql = "SELECT * FROM categories WHERE id=$id";
$result = mysqli_query($conn, $sql);
$category = mysqli_fetch_assoc($result);
include("../includes/header.php");
include("../includes/sidebar.php");

?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <form method="post" enctype="multipart/form-data">
                <h2>Edit Category</h2>
                <?php if (isset($fmsg)) {
                    echo $fmsg;
                } ?>
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" name="category_name" class="form-control" id="category_name" value="<?php echo $category['category_name']; ?>" placeholder="Category Name" />
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent ID</label>
                    <select name="parent_id" class="form-control">
                        <option value="0">Parent Category</option>
                        <?php
                        $sql = "SELECT * FROM categories WHERE parent_id=0";
                        $res = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($res)) { ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo $r['category_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_description">Category Description</label>
                    <textarea name="category_description" class="form-control" id="category_description" rows="3"><?php echo $category['category_description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="form">Active</label>
                    <select name="active" class="form-control">
                        <option value="0" <?php if ($category['active'] == 0) {
                                                echo "selected";
                                            } ?>>No</option>
                        <option value="1" <?php if ($category['active'] == 1) {
                                                echo "selected";
                                            } ?>>Yes</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                </br>
                <input type="submit" class="btn btn-primary col-md-2 col-md-offset-10" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php
include("../includes/footer.php");
?>