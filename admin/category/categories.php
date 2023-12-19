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
$sql = "SELECT * FROM categories";
$result = mysqli_query($conn, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Categories list</h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Category ID</th>
                        <th scope="col">Parent ID</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) { ?>
                        <tr>
                            <td scope="row"><?php echo $category['id']; ?></td>
                            <td><?php echo $category['parent_id']; ?></td>
                            <td><?php echo $category['category_name']; ?></td>
                            <td><?php echo $category['active']; ?></td>
                            <td>
                                <a class="btn btn-primary" href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delete_category.php?id=<?php echo $category['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
            <a class="btn btn-success float-end" href="add_category.php">Add new
                cagetory</a>
        </div>
    </div>
</div>

<?php
include("../includes/footer.php");
?>