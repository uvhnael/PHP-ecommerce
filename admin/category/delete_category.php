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
if (isset($_GET['id']) & !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) <= 0) {
        header('location: categories.php');
    }
} else {
    header('location: categories.php');
}
if (isset($_POST) & !empty($_POST)) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM categories WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM product_categories WHERE category_id='$id'";
    $result = mysqli_query($conn, $sql);

    header('location: categories.php');
}
include("../includes/header.php");
include("../includes/sidebar.php");
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <form method="post">
                <h2>Delete Category</h2>
                <?php if (isset($fmsg)) {
                    echo $fmsg;
                } ?>
                <div class="form-group">
                    <label for="categoryname">Category Name</label>
                    <input type="text" name="categoryname" class="form-control" id="categoryname" value="<?php echo $category['category_name']; ?>" placeholder="Category Name" readonly />
                </div>
                <div class="form-group">
                    <label for="parentid">Parent ID</label>
                    <input type="text" name="parentid" class="form-control" id="parentid" value="<?php echo $category['parent_id']; ?>" placeholder="Parent ID" readonly />
                </div>
                <div class="form-group">
                    <label for="active">Active</label>
                    <input type="text" name="active" class="form-control" id="active" value="<?php echo $category['active']; ?>" placeholder="Parent ID" readonly />
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