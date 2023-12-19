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


if (isset($_POST) & !empty($_POST)) {
    $staff_id = $_POST['staff_id'];
    $sql = "DELETE FROM staffs WHERE id=$staff_id";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        header("Location: staffs.php");
    } else {
        $fmsg = "Failed to Delete Data.";
    }
}

if (isset($_GET) & !empty($_GET)) {
    $staff_id = $_GET['staff_id'];
}

include '../includes/header.php';
include '../includes/sidebar.php';

$sql = "SELECT * FROM staffs WHERE id=$staff_id";
$res = mysqli_query($conn, $sql);
$staff = mysqli_fetch_assoc($res);

?>

<div class="container section-padding">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <h2>Delete Staff account</h2>
            <?php if (isset($fmsg)) {
                echo $fmsg;
            } ?>

            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="staff_id">Staff ID</label>
                    <input type="text" class="form-control" id="staff_id" name="staff_id" placeholder="Staff ID" value="<?php echo $staff['id']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" readonly class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo $staff['first_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" readonly class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $staff['last_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" readonly class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number" value="<?php echo $staff['phone_number']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" readonly class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $staff['email']; ?>">
                </div>
                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-danger ">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>