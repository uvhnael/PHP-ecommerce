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

$sql = "SELECT * FROM staffs";
$res = mysqli_query($conn, $sql);

$staffs = mysqli_fetch_all($res, MYSQLI_ASSOC);

?>

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">

            <h2>Staffs</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staffs as $staff) { ?>
                        <tr>
                            <td><?php echo $staff['id']; ?></td>
                            <td><?php echo $staff['last_name'] . " " . $staff['first_name']; ?></td>
                            <td><?php echo $staff['email']; ?></td>
                            <td><?php echo $staff['phone_number']; ?></td>
                            <td><?php
                                $sql = "SELECT * FROM roles INNER JOIN staff_roles ON staff_roles.role_id = roles.id WHERE staff_roles.staff_id = {$staff['id']}";
                                $res1 = mysqli_query($conn, $sql);
                                $role = mysqli_fetch_assoc($res1);
                                ?>
                                <select class="form-control" id="<?php echo $staff['id']; ?>" onchange="update_role(this)">
                                    <option value="<?php echo $role['role_id'];; ?>"><?php echo $role['role_name']; ?>
                                    </option>
                                    <?php
                                    $sql = "SELECT * FROM roles WHERE id != {$role['role_id']}";
                                    $res = mysqli_query($conn, $sql);
                                    while ($r = mysqli_fetch_assoc($res)) {
                                    ?>
                                        <option value="<?php echo $r['id']; ?>"><?php echo $r['role_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <a class="btn btn-danger" href="delete_staff.php?staff_id=<?php echo $staff['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <a class="btn btn-success float-end" href="add_staff.php">Add Staff</a>
        </div>
    </div>
</div>

<script>
    function update_role(e) {
        var staff_id = e.id;
        var role_id = e.value;
        console.log(staff_id);
        console.log(role_id);

        $.ajax({
            url: "set_role.php",
            type: "POST",
            data: {
                staff_id: staff_id,
                role_id: role_id
            },
            success: function(data) {

            }
        });
    }
</script>

<?php include '../includes/footer.php'; ?>