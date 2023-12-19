<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Staff Manager') {
        header('location: ../index.php');
    }
}

include_once('../includes/db.php');

include '../includes/header.php';
include '../includes/sidebar.php';

$sql = "SELECT * FROM roles";
$res = mysqli_query($conn, $sql);
$roles = mysqli_fetch_all($res, MYSQLI_ASSOC);

?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">

            <h2>Roles</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Privileges</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role) { ?>
                        <tr>
                            <td><?php echo $role['id']; ?></td>
                            <td><?php echo $role['role_name']; ?></td>
                            <td><?php echo $role['privileges'] ?></td>
                            <td>
                                <a class="btn btn-danger" href="delete_role.php?role_id=<?php echo $role['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="btn btn-primary" href="add_role.php">Add Role</a>