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
include '../includes/header.php';
include '../includes/sidebar.php';

$sql = "SELECT * FROM tags";
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Tags</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tag Name</th>
                        <th>Icon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, $sql);
                    while ($r = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo $r['tag_name']; ?></td>
                            <td><?php echo $r['icon']; ?></td>
                            <td>
                                <a class="btn btn-primary" href="edit_tag.php?tag_id=<?php echo $r['id']; ?>">Edit</a>
                                <a class="btn btn-danger" href="delete_tag.php?tag_id=<?php echo $r['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="btn btn-success float-end" href="add_tag.php">Add new tag</a>

        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>