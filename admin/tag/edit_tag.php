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

if (isset($_GET) & !empty($_GET)) {
    $tag_id = $_GET['tag_id'];
}


if (isset($_POST) & !empty($_POST)) {
    $tag_name = mysqli_real_escape_string($conn, $_POST['tag_name']);
    $icon = mysqli_real_escape_string($conn, $_POST['icon']);

    $sql = "UPDATE tags SET tag_name='$tag_name', icon='$icon' WHERE id='$tag_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("location: tags.php");
    } else {
        $error = "Failed to update tag";
    }
}


$sql = "SELECT * FROM tags WHERE id='$tag_id'";
$result = mysqli_query($conn, $sql);
$tag = mysqli_fetch_assoc($result);


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
                    <input type="text" name="tag_name" class="form-control" id="tag_name" value=<?php echo $tag['tag_name']; ?>>
                </div>
                <div class="form-group">
                    <label for="icon">Icon</label>
                    <input type="text" name="icon" class="form-control" id="icon" value=<?php echo $tag['icon']; ?>>
                </div>

                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>