<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Merchandize Manager') {
        header('location: ../index.php');
    }
}

if (isset($_POST) & !empty($_POST)) {
    $tag_name = $_POST['tag_name'];
    $icon = $_POST['icon'];
    $created_by = $_SESSION['id'];

    $sql = "INSERT INTO tags (tag_name, icon, created_by) VALUES ('$tag_name', '$icon', '$created_by')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header('location: tags.php');
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Add Tags</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="tag_name">Tag Name</label>
                    <input type="text" name="tag_name" class="form-control" id="tag_name" placeholder="Tag Name">
                </div>
                <div class="form-group">
                    <label for="icon">Icon</label>
                    <input type="text" name="icon" class="form-control" id="icon" placeholder="Icon">
                </div>

                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>