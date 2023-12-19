<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 3 && $role_name != 'Admin') {
        header('location: ../index.php');
    }
}
include_once('../includes/db.php');

include '../includes/header.php';
include '../includes/sidebar.php';

$sql = "SELECT * FROM customers";
$res = mysqli_query($conn, $sql);
$customers = mysqli_fetch_all($res, MYSQLI_ASSOC);
?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">

            <h2>Customers</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer) { ?>
                        <tr>
                            <td><?php echo $customer['id']; ?></td>
                            <td><?php echo $customer['last_name'] . " " . $customer['first_name']; ?></td>
                            <td><?php echo $customer['email']; ?></td>
                            <td><?php echo $customer['phone_number']; ?></td>
                            <td>
                                <a class="btn btn-danger" href="delete_customer.php?customer_id=<?php echo $customer['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>