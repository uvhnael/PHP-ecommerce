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
    $categoryname = $_POST['category_name'];
    $parent_id = $_POST['parent_id'];
    $categorydescription = $_POST['category_description'];
    $active = $_POST['active'];
    $createdby = $_SESSION['id'];
    $updatedby = $_SESSION['id'];
    $sql = "INSERT INTO `categories`(`parent_id`, `category_name`, `active`,`category_description`, `created_by`, `updated_by`) VALUES ( '$parent_id', '$categoryname', '$active', '$categorydescription', '$createdby', '$updatedby')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header('location: categories.php');
    } else {
        $fmsg = "Failed to Add Category";
    }
}
include("../includes/header.php");
include("../includes/sidebar.php");
$sql = "SELECT * FROM categories WHERE parent_id=0";
$res = mysqli_query($conn, $sql);
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <form method="post" enctype="multipart/form-data">
                <h2>Add Category</h2>
                <?php if (isset($fmsg)) {
                    echo $fmsg;
                } ?>
                <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" name="category_name" class="form-control" id="category_name" placeholder="Category Name" />
                </div>
                <div class="form-group">
                    <label for="parent_id">Parent ID</label>
                    <select name="parent_id" class="form-control">
                        <option value="0">Parent Category</option>
                        <?php
                        $sql = "SELECT * FROM categories WHERE parent_id=0";
                        $res = mysqli_query($conn, $sql);
                        while ($r = mysqli_fetch_assoc($res)) { ?>
                            <option value="<?php echo $r['id']; ?>">
                                <?php echo $r['category_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_description">Category Description</label>
                    <textarea name="category_description" class="form-control" id="category_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="form">Active</label>
                    <select name="active" class="form-control">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                </br>
                <input type="submit" class="btn btn-primary col-md-2 col-md-offset-10" value="Submit" />
            </form>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
?>